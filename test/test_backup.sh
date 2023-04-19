#! /bin/bash
# Tests the backup shell script
# Run this test from within the backup container

# internal Docker URL directly to main page (as set in docker-compose), avoid redirects
WIKI_URL="wikibase-docker.svc/wiki"
WIKI_URL_MAIN="${WIKI_URL}/Main_Page"
IMG_URL="wikibase-docker.svc/w/images"
TIME_START=$(date +%s)

# count *.gz files in /data dir
_count_backup_files() {
    shopt -s nullglob # make sure the next line return 0 if no files match
    bfiles=(/data/*.gz) # only count *.gz files
    echo ${#bfiles[@]}
}

# get the http response code for WIKI_URL_MAIN
_get_wiki_http_response_code() {
    url=$WIKI_URL_MAIN
    if [[ $1 ]]; then
        url=$1
    fi
    curl --write-out '%{http_code}' --head --silent --output /dev/null "$url"
}

# break the test wiki
_break_wiki() {
    # Erase all pages (break the wiki)
    mysql -u"${DB_USER}" -p"${DB_PASS}" -h"${DB_HOST}" --database "${DB_NAME}" -Bse 'TRUNCATE TABLE page'
}


test_1() {
    echo "Test that backups are stored in the backup dir"
    # count length of /data/backup.log before backup (will be empty on CI or clean
    # system, but not on a running system)
    if [[ -f /data/backup.log ]]; then
        log_length=$(wc -l </data/backup.log)
    else
        log_length=0
    fi

    # Run backup script, count backup files before and after
    file_count_before=$(_count_backup_files)
    /app/backup.sh &>/dev/null
    file_count_after=$(_count_backup_files)

    
    # Check that 3 backup files have been created (SQL-, XML-, uploaded-files- backups)
    # Please keep the spacing after [[ and around ==
    if [[ $((file_count_before+3)) == "$file_count_after" ]]; then
        echo " - Test backup OK: $((file_count_after - file_count_before))/3 backup files were created."
    else
        echo " - Test backup FAILED: $((file_count_after - file_count_before))/3 backup files were created:"
        files_created=($(ls -tr /data/*.gz | tail -n $((file_count_after - file_count_before))))
        for backup_file in "${files_created[@]}"; do
            echo "      $backup_file"
        done
        echo "      MISSING: "
        if [[ "${files_created[*]}" != *portal_db_backup_*.gz*  ]]; then
            echo "          SQL backup"
        fi
        if [[ "${files_created[*]}" != *portal_xml_backup*.gz*  ]]; then
            echo "          XML backup"
        fi
        if [[ "${files_created[*]}" != *images_*.gz*  ]]; then
            echo "          Images backup"
        fi
        # dump last record of backup.log
        echo ""
        echo "/data/backup.log (last record):"
        tail -n +"$log_length" /data/backup.log

        exit 1
    fi
}

test_2() {
    echo "Test that the database can be restored from SQL backup"

    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore SQL FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi

    # Run backup script
    /app/backup.sh &>/dev/null

    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore SQL FAILED: Something went wrong while erasing pages of wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run restore script with defaults
    /app/restore.sh -t sql &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore SQL FAILED: Could not restore wiki at $WIKI_URL_MAIN."
        exit 1
    fi    
    echo " - Test restore SQL OK: Wiki was restored from SQL dump."
}

test_3() {
    echo "Test that the database can be restored from XML backup"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore XML FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run backup script
    /app/backup.sh &>/dev/null
    
    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore XML FAILED: Something went wrong while erasing pages of wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run restore script from last XML backup
    /app/restore.sh -t xml &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore XML FAILED: Could not restore wiki at $WIKI_URL_MAIN."
        exit 1
    fi    
    echo " - Test restore XML OK: Wiki was restored from XML dump."
}

test_4() {
    metrics_file="/data/backup_full.prom"
    echo "Test that a metrics file $metrics_file was written"

    TIME_MOD=$(stat --printf=%Y $metrics_file)

    if [ -s $metrics_file ]; then
        # file exists. check that file was modified after calling this script
        if [ "$TIME_MOD" -ge "$TIME_START" ]; then
            echo " - Test metrics file OK: $metrics_file exists, is non-empty and recent"
        else
            echo " - Test metrics file FAILED: $metrics_file exists, is non-empty but old"
            exit 1
        fi

    elif [ -f $metrics_file ]; then
        echo " - Test metrics file FAILED: $metrics_file exists and but is empty"
        exit 1
    else
        echo " - Test metrics file FAILED: $metrics_file does not exist"
        exit 1
    fi
}


test_5() {
    # 1. upload a test image file via importImages.php
    # 2. create a backup
    # 3. delete the file from /var/www/html/images
    # 4. restore the backup of the images directory
    # 5. check if the file is available
    # 6. cleanup: delete the file again with deleteBatch.php and from images

    echo "Test that the uploaded images can be restored from the image backup"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code $WIKI_URL_MAIN)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi

    ## 1. upload test image
    # first import the test image. modify file name with a random string to avoid
    # accidentally finding a previously uploaded file
    # NOTE: this could also be handled with mktemp
    rand_str=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 8)
    rand_dir="/tmp/${rand_str}"
    # NOTE: file name should start with capital letter, since importimages.php
    # automatically enforces this. capitalize a lower case variable with ${var^}.
    test_image="Test_image_backup_${rand_str}.png"
    mkdir "$rand_dir" && cp /test/test_image_backup.png "${rand_dir}/${test_image}"
    if \
        ! su -l www-data -s /bin/bash -c 'php /var/www/html/maintenance/importImages.php --conf /shared/LocalSettings.php --comment "TEST importing images backup" '"$rand_dir" >/dev/null
    then
        echo " - Test restore images FAILED: Error uploading image file with importImages.php! (status $?)"
        exit 1
    fi

    # check if file is reachable
    response=$(_get_wiki_http_response_code "${WIKI_URL}/File:${test_image}")
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: imported image file not reachable on the server! (status $response)"
        echo "      (url: ${WIKI_URL}/File:${test_image})"
        exit 1
    fi

    ## 2. backup
    /app/backup.sh &>/dev/null

    ## 3. delete files from storage
    found_file=$(cd /var/www/html/images && find . -name "*$test_image" -print -quit |\
        sed 's/^\.\///')
    if ! find /var/www/html/images -name "*${test_image}" -delete
    then
        echo " - Test restore images FAILED: uploaded file not found in /var/www/html/images! (status $?)"
        exit 1
    else
        # delete all empty folders, too
        find /var/www/html/images -name "*" -type d -delete
    fi

    response=$(_get_wiki_http_response_code "${IMG_URL}/${found_file}")
    if [[ ! $response == '404' ]]; then
        echo " - Test restore images FAILED: deleted file was found at ${IMG_URL}/${found_file}. (status $response)"
        exit 1
    fi

    ## 4. restore image backup
    /app/restore.sh -t img &>/dev/null

    ## 5. check if file was restored
    # NOTE: only checks for original file, not deleted or archived files!
    # find restored file on disk
    found_restored=$(cd /var/www/html/images && find . -name "$test_image" -print -quit)
    if  [[ -z  $found_restored ]]; then
        echo " - Test restore images FAILED: restored file not found on the disk!"
        exit 1
    fi

    # find restored file on server
    response=$(_get_wiki_http_response_code "${IMG_URL}/${found_restored}")
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: restored file was not found at ${IMG_URL}/${found_restored}."
        exit 1
    fi

    ## 6. cleanup: delete files
    tempfile="$rand_dir/tmp.$rand_str"
    echo "File:${test_image}" > "$tempfile"
    if \
        ! php /var/www/html/maintenance/deleteBatch.php \
            --conf /shared/LocalSettings.php \
            --r "delete temp test file" "$tempfile" >/dev/null
    then
        echo " - Test restore images TEST ERROR: Error DELETING temporary image test file!"
        exit 1
    fi

    rm -rf "$rand_dir"

    echo " - Test restore images OK: deleted image successfully restored from backup"
}


################################################################################

test_1
test_2
test_3
test_4
test_5

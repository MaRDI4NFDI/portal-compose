#! /bin/bash
# Tests the backup shell script
# Run this test from within the backup container

# internal Docker URL directly to main page (as set in docker-compose), avoid redirects
WIKI_URL="wikibase-docker.svc/wiki"
WIKI_URL_MAIN="${WIKI_URL}/Main_Page"
TIME_START=$(date +%s)

# count *.gz files in /data dir
_count_backup_files() {
    shopt -s nullglob # make sure the next line return 0 if no files match
    bfiles=(/data/*.gz) # only count *.gz files
    echo ${#bfiles[@]}
}

# get the http response code for WIKI_URL_MAIN
_get_wiki_http_response_code() {
    curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_URL_MAIN
}

# break the test wiki
_break_wiki() {
    # Erase all pages (break the wiki)
    mysql -u"${DB_USER}" -p"${DB_PASS}" -h"${DB_HOST}" --database "${DB_NAME}" -Bse 'TRUNCATE TABLE page'
}


test_1() {
    echo "Test that backups are stored in the backup dir"
    # Run backup script, count backup files before and after
    file_count_before=$(_count_backup_files)
    /app/backup.sh &>/dev/null
    file_count_after=$(_count_backup_files)
    
    # Check that 3 backup files have been created (SQL-, XML-, uploaded-files- backups)
    # Please keep the spacing after [[ and around ==
    if [[ $((file_count_before+3)) == "$file_count_after" ]]; then
        echo " - Test backup OK: $((file_count_after - file_count_before))/3 backup files where created."
    else
        echo " - Test backup FAILED: $((file_count_after - file_count_before))/3 backup where files created."
        exit 1
    fi
}

test_2() {
    echo "Test that the database can be restored from SQL backup"

    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore SQL FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi

    # Run backup script
    /app/backup.sh &>/dev/null

    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore SQL FAILED: Something went wrong while erasing pages of wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run restore script with defaults
    /app/restore.sh -t sql &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore SQL FAILED: Could not restore wiki at $WIKI_URL_MAIN."
        exit 1
    fi    
    echo " - Test restore SQL OK: Wiki was restored from SQL dump."
}

test_3() {
    echo "Test that the database can be restored from XML backup"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore XML FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run backup script
    /app/backup.sh &>/dev/null
    
    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore XML FAILED: Something went wrong while erasing pages of wiki at $WIKI_URL_MAIN."
        exit 1
    fi
    
    # Run restore script from last XML backup
    /app/restore.sh -t xml &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
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
    # cleanup: delete the file again with deleteBatch.php and from images
    echo "Test that the uploaded images can be restored from image backup"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: Could not locate wiki at $WIKI_URL_MAIN."
        exit 1
    fi

    ## 1. upload test image
    # first import the test image. modify file name with a random string to avoid
    # accidentally finding a previously uploaded file
    rand_str="$(echo $RANDOM | base64 | head -c 8)"
    rand_dir="/tmp/${rand_str}"
    test_image="test_image_backup__${rand_dir}.png"
    mkdir "$rand_dir" && cp /test/test_image_backup.png "${rand_dir}/${test_image}"
    if \
        ! su -l www-data -s /bin/bash -c 'php /var/www/html/maintenance/importImages.php --conf /shared/LocalSettings.php --comment "TEST importing images backup" '"$rand_dir"
    then
        echo " - Test restore images FAILED: Error uploading image file with importImages.php!"
        exit 1
    fi

    ## 2. backup
    /app/backup.sh &>/dev/null

    # check if file is reachable
    response=$(curl --write-out '%{http_code}' --head --silent --output /dev/null "${WIKI_URL}/File:${test_image}")
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: Something went wrong with importing a test image file."
        exit 1
    fi

    ## 3. delete files from storage
    if ! find /var/www/html/images -name ".*${test_image}" -print -depth
    then
        # find /var/www/html/images -name ".*${test_image}" -delete
        echo " - Test restore images ERROR: uploaded file not found in /var/www/html/images!"
        # exit 1
    else
        echo "files found and deleted"
    fi

    ## 4. restore image backup
    /app/restore.sh -t img &>/dev/null


    ## 5. 
    response=$(curl --write-out '%{http_code}' --head --silent --output /dev/null "${WIKI_URL}/File:${test_image}")
    if [[ ! $response == '200' ]]; then
        echo " - Test restore images FAILED: Image backupo not restored correctly."
        exit 1
    fi

    # XXXXX CONTINUE HERE! 
     ...


    if \
        ! php /var/www/html/maintenance/deleteBatch.php \
            --conf /shared/LocalSettings.php \
            --reason "delete temp test file" <(echo "File:${test_image}")
    then
        echo " - Test restore images TEST ERROR: Error DELETING temporary image test file!"
        exit 1
    fi

    rm -rf "$rand_dir"


    response_deleted=$(curl --write-out '%{http_code}' --head --silent --output /dev/null "${WIKI_URL}/File:test_image_backup__${rand_dir}.png")
    echo "RESPONSE DELETED: $response_deleted"

    if [[ ! $response == '200' ]] || [[ ! $response_deleted == '404' ]]; then
        if [[ ! $response == '200' ]]; then
            echo " - Test restore images FAILED: Something went wrong with importing a test image file."
        else
            echo " - Test restore images FAILED: Something went wrong while erasing temp. image files."
        fi
        exit 1
    fi


    ... CONITNUE HERE!
    ... PROBLEM: since the pages are deleted by deleteBatch, simply copying back the
    file wont help probably.
    The better test would be to import the file, then delete it on the disk, then
    restore it and check if it is accessible. then, finally, the page can be deleted
    again.

}


################################################################################

test_1
test_2
test_3
test_4

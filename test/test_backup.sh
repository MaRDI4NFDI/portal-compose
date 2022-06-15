#! /bin/bash
# Tests the backup shell script
# Run this test from within the backup container

# internal Docker URL directly to main page (as set in docker-compose), avoid redirects
WIKI_URL='wikibase-docker.svc/wiki/Main_Page' 
TIME_START=$(date +%s)

# count *.gz files in /data dir
_count_backup_files() {
    shopt -s nullglob # make sure the next line return 0 if no files match
    bfiles=(/data/*.gz) # only count *.gz files
    echo ${#bfiles[@]}
}

# get the http response code for WIKI_URL
_get_wiki_http_response_code() {
    curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_URL
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
        echo " - Test restore SQL FAILED: Could not locate wiki at $WIKI_MAIN_URL."
        exit 1
    fi

    # Run backup script
    /app/backup.sh &>/dev/null

    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore SQL FAILED: Something went wrong while erasing pages of wiki at $WIKI_MAIN_URL."
        exit 1
    fi
    
    # Run restore script with defaults
    /app/restore.sh -t sql &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore SQL FAILED: Could not restore wiki at $WIKI_MAIN_URL."
        exit 1
    fi    
    echo " - Test restore SQL OK: Wiki was restored from SQL dump."
}

test_3() {
    echo "Test that the database can be restored from XML backup"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore XML FAILED: Could not locate wiki at $WIKI_MAIN_URL."
        exit 1
    fi
    
    # Run backup script
    /app/backup.sh &>/dev/null
    
    # Break the wiki
    _break_wiki
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        echo " - Test restore XML FAILED: Something went wrong while erasing pages of wiki at $WIKI_MAIN_URL."
        exit 1
    fi
    
    # Run restore script from last XML backup
    /app/restore.sh -t xml &>/dev/null
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        echo " - Test restore XML FAILED: Could not restore wiki at $WIKI_MAIN_URL."
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

################################################################################

test_1
test_2
test_3
test_4

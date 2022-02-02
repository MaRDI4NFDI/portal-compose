#! /bin/bash
# Tests the backup shell script
# Run this test from within the backup container

# internal Docker URL directly to main page (as set in docker-compose), avoid redirects
WIKI_URL='wikibase-docker.svc/wiki/Main_Page' 

# count *.gz files in /data dir
_count_backup_files() {
    shopt -s nullglob # make sure the next line return 0 if no files match
    bfiles=(/data/*.gz) # only count *.gz files
    echo ${#bfiles[@]}
}

# get the http response code for WIKI_URL
_get_wiki_http_response_code() {
    echo $(curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_URL)
}

# break the test wiki
_break_wiki() {
    # Erase all pages (break the wiki)
    mysql -u${DB_USER} -p${DB_PASS} -h${DB_HOST} --database ${DB_NAME} -Bse 'TRUNCATE TABLE page'
}


test_1() {
    printf "Test that backups are stored in the backup dir\n"
    # Run backup script, count backup files before and after
    file_count_before=$(_count_backup_files)
    /app/backup.sh
    file_count_after=$(_count_backup_files)
    
    # Check that 3 backup files have been created (SQL-, XML-, uploaded-files- backups)
    # Please keep the spacing after [[ and around ==
    if [[ $(($file_count_before+3)) == $file_count_after ]]; then
        printf ' - Test backup OK: Three backup files where created.\n'
    else
        printf ' - Test backup FAILED: no backup where files created.\n'
        exit 1
    fi
}


test_2() {
    printf "Test that the database can be restored from SQL backup\n"

    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        printf " - Test restore SQL FAILED: Could not locate wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi

    # Run backup script
    /app/backup.sh

    # Break the wiki
    $(_break_wiki)
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        printf " - Test restore SQL FAILED: Something went wrong while erasing pages of wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi
    
    # Run restore script with defaults
    /app/restore.sh
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        printf " - Test restore SQL FAILED: Could not restore wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi    
    printf ' - Test restore SQL OK: Wiki was restored from SQL dump.\n'
}

test_3() {
    printf "Test that the database can be restored from XML backup\n"
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        printf " - Test restore XML FAILED: Could not locate wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi
    
    # Run backup script
    /app/backup.sh
    
    # Break the wiki
    $(_break_wiki)
    
    # Check that wiki is broken
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '404' ]]; then
        printf " - Test restore XML FAILED: Something went wrong while erasing pages of wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi
    
    # Run restore script from last XML backup
    /app/restore.sh -t xml
    
    # Check that wiki is running and accessible
    response=$(_get_wiki_http_response_code)
    if [[ ! $response == '200' ]]; then
        printf " - Test restore XML FAILED: Could not restore wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi    
    printf ' - Test restore XML OK: Wiki was restored from XML dump.\n'
}

test_1
test_2
test_3
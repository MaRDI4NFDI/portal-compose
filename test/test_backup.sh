#! /bin/bash
# Tests the backup shell script
# Run this test from within the backup container

# count *.gz files in /data dir
_count_backup_files() {
    shopt -s nullglob # make sure the next line return 0 if no files match
    bfiles=(/data/*.gz) # only count *.gz files
    echo ${#bfiles[@]}
}


test_1() {
    printf "Test that backups are stored in the backup dir\n"
    # Run backup script, count backup files before and after
    file_count_before=$(_count_backup_files)
    /app/backup.sh
    file_count_after=$(_count_backup_files)
    
    # Check that 2 backup files have been created (SQL and XML backups)
    # Please keep the spacing after [[ and around ==
    if [[ $(($file_count_before+2)) == $file_count_after ]]; then
        printf ' - Test backup OK: Two backup files where created.\n'
    else
        printf ' - Test backup FAILED: no backup where files created.\n'
        exit 1
    fi
}

test_2() {
    printf "Test that the database can be restored from SQL backup\n"

    # internal Docker URL directly to main page (as set in docker-compose), avoid redirects
    WIKI_MAIN_URL='wikibase-docker.svc/wiki/Main_Page' 
    
    # Check that wiki is running and accessible
    response=$(curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_MAIN_URL)
    if [[ ! $response == '200' ]]; then
        printf " - Test backup FAILED: Could not locate wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi

    # Run backup script
    /app/backup.sh

    # Erase all pages (break the wiki)
    mysql -u${DB_USER} -p${DB_PASS} -h${DB_HOST} --database ${DB_NAME} -Bse 'TRUNCATE TABLE page'
    
    # Check that wiki is broken
    response=$(curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_MAIN_URL)
    if [[ ! $response == '404' ]]; then
        printf " - Test backup FAILED: Something went wrong while erasing pages of wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi
    
    # Run restore script with defaults
    /app/restore.sh
    
    # Check that wiki is running and accessible
    response=$(curl --write-out '%{http_code}' --head --silent --output /dev/null $WIKI_MAIN_URL)
    if [[ ! $response == '200' ]]; then
        printf " - Test backup FAILED: Could not restore wiki at ${WIKI_MAIN_URL}.\n"
        exit 1
    fi    
    printf ' - Test backup OK: Wiki was restored from SQL dump.\n'
}

test_1
test_2

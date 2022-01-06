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
    file_count_before=$(_count_backup_files)
    /app/wrapper.sh
    file_count_after=$(_count_backup_files)
    
    # note the spacing after [[ and around ==
    if [[ $(($file_count_before+2)) == $file_count_after ]]; then
        printf ' - Test backup OK: Two backup files where created.\n'
        exit 0
    else
        printf ' - Test backup FAILED: no backup where files created.\n'
        exit 1
    fi
}

test_1
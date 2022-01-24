#!/usr/bin/bash

printf "\nTests that hit the GUI using Selenium"
printf "\n----------------------------------------\n"
docker exec mardi-selenium bash ./start_test_runner.sh test &&\
printf "\nTest the backup functions" &&\
printf "\n----------------------------------------\n" &&\
docker exec portal-compose_backup_1 bash /test/test_backup.sh

printf "\n"

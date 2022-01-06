#!/usr/bin/bash

# run tests that hit the GUI using Selenium
docker exec mardi-selenium bash ./start_test_runner.sh test/

# Run backup tests
docker exec -ti portal-compose_backup_1 bash /test/test_backup.sh
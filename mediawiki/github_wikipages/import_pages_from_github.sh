#!/bin/bash

# root@e9c579282944:/var/www/html/maintenance# php edit.php 

readonly REPOSITORY=https://github.com/MaRDI4NFDI/operations-puppet-mardi.git/trunk/files
#readonly STORAGEPATH=/home/johannes/Repositories/portal-compose/mediawiki/github_wikipages
readonly STORAGEPATH=.


# This goes to external mounted directory 
#svn checkout $REPOSITORY $STORAGEPATH

# This is triggered within the wikibase-bundle container

# might be triggered externally docker exec mycontainer /path/to/test.sh
for filename in $STORAGEPATH/*.wiki; do
    echo $filename  
    echo $(basename "$filename" .wiki)
    php /var/www/html/maintenance/edit.php "GitHub:$(basename "$filename" .wiki)" < $filename
done




# atm mounting       - ./mediawiki/github_wikipages:/var/www/html/github_wikipages   to get the pages from host to container 

# portal-compose
docker-composer repo for MaRDI

## Local installation
```
git clone --recurse-submodules git@github.com:MaRDI4NFDI/portal-compose.git
cd portal-compose
cp ./mediawiki/template.env ./.env
```

Change parameters for your local installation in .env as required, this file will not be committed.
Change at least the passwords and secret to any password for local usage:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
Add the following lines at the end of your .env file, edit as required
```
# Local settings
WIKIBASE_HOST=localhost
WIKIBASE_PORT=8080    
WDQS_FRONTEND_PORT=8834
QUICKSTATEMENTS_HOST=localhost
QUICKSTATEMENTS_PORT=8840
GRAFANA_PORT=3000
RESTART='no'
```

## Start up the containers
Start-up the containers from the docker-compose file for development:
```
docker-compose -f docker-compose.yml -f docker-compose-dev.yml up -d
```
Stop the containers:
```
docker-compose -f docker-compose.yml -f docker-compose-dev.yml down
```

(Tipp: add these two commands to your `~/.bash_aliases`)

The local install has 2 additional containers:
* Selenium for running tests
* Openrefine for data manipulation

The local install also has open ports, so that the services can be accessed without using the reverse proxy
* Wikibase, http://localhost:8080
* WDQS Frontend, http://localhost:8834
* Quickstatements, http://localhost:8840
* WB query service frontend, http://localhost:8834
* OpenRefine, http://localhost:3333

Note that the containers for local development are set to not restart, 
so that they do not start automatically when you start your computer.

### Test locally
Run the tests: `bash ./run_tests.sh`

## Build on CI 
The containers will be built and tested after each push on the main branch: 

Preparations **this has already been done on GitHub**:
* create a [GitHub environment](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment) 
* call it "staging" (specified in .github/workflows/main.yml)
* set (required) these to test passwords:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
* also set these environment variables:
```
WIKIBASE_HOST=localhost
WIKIBASE_PORT=8080

WDQS_FRONTEND_HOST=localhost
WDQS_FRONTEND_PORT=8834

QUICKSTATEMENTS_HOST=localhost
QUICKSTATEMENTS_PORT=8840

WB_PUBLIC_HOST_AND_PORT=localhost:8080
QS_PUBLIC_HOST_AND_PORT=localhost:8840

MW_ELASTIC_HOST=localhost
MW_ELASTIC_PORT=9200
```
## Deploy on the MaRDI server
* create a .env file (the defaults should be OK for the MaRDI server)
```
cp ./mediawiki/template.env ./.env
```
* set the passwords and key to real passwords in the .env file:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
## More
See [Discussion in the project's wiki](https://github.com/MaRDI4NFDI/portal-compose/wiki)

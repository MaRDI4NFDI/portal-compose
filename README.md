# portal-compose
docker-composer repo for MaRDI

## Local installation
```
git clone --recurse-submodules git@github.com:MaRDI4NFDI/portal-compose.git
cd portal-compose
cp ./mediawiki/template.env ./.env
```

Change parameters for your local installation in .env as required, this file will not be committed.
Set at least:
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
Change the passwords and secret to any password for local usage:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```

In case of a clean install, remove the corresponding Docker volumes.

## Test locally
Make sure the Wikibase and Quickstatement hosts point to localhost, then start-up the containers from the docker-compose file for development:
```
docker-compose -f docker-compose-dev.yml up -d
```

Wiki is on http://localhost:8080

Run the tests: `bash ./run_tests.sh`

## Build on CI 
The containers will be built and tested after each push on the main branch: 

Preparations (this has already been done on GitHub):
* create a [GitHub environment](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment) 
* call it "staging" (specified in .github/workflows/main.yml)
* set (required) these to test passwords:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
* also set:
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
* set the passwords and key to real passwords in the .env file:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
## More
See [Discussion in the project's wiki](https://github.com/MaRDI4NFDI/portal-compose/wiki)

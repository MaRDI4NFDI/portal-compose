# portal-compose
docker-composer repo for mardi

## Local installation`
```
git clone --recurse-submodules git@github.com:MaRDI4NFDI/portal-compose.git
cp ./mediawiki/template.env to ./.env
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

In case of a clean install, cleanup the Docker volumes.

## Test locally
Make sure the Wikibase and Quickstatement hosts point to localhost, then start-up the containers:
```
docker-compose up -d
```

Run the tests: `bash ./run_tests.sh`

## Build on CI 
To build the containers on GitHub CI: 

* create a [GitHub environment](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment) 
* call it "staging" (specified in .github/workflows/main.yml)
* set at least:
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
* change (required) these to test passwords:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```

## Deploy on the MaRDI server
* copy mediawiki/template.env to .env (the defaults should be OK for the MaRDI server)
* change the passwords and key to real passwords:
```
MW_SECRET_KEY=some-secret-key
MW_ADMIN_PASS=change-this-password
DB_PASS=change-this-sqlpassword
```
## More
See [Discussion in the project's wiki](https://github.com/MaRDI4NFDI/portal-compose/wiki)

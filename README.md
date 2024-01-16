# portal-compose for development
reduced docker-compose repository for MaRDI development

## Local installation
```
git clone -b dev_stack git@github.com:MaRDI4NFDI/portal-compose.git
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

The local install also has open ports
* Wikibase, http://localhost:8080
* WDQS Frontend, http://localhost:8834

Note that the containers for local development are set to not restart
so that they do not start automatically when you start your computer.

## Start up the containers
Start-up the containers from the docker-compose file for development:
```
docker-compose up -d
```
Stop the containers:
```
docker-compose down
```

Hint: make a local copy `cp docker-compose-dev.yml docker-compose.override.yml`, where local changes can be made if necessary. The override file is read automatically by docker compose when calling
```
docker-compose up -d | down | logs
```
See [here](https://docs.docker.com/compose/extends/) and [here](https://docs.docker.com/compose/extends/#adding-and-overriding-configuration) for further info.


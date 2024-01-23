# portal-compose
docker-compose repository for MaRDI

## Local installation
```
git clone git@github.com:MaRDI4NFDI/portal-compose.git
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

The local install has 2 additional containers:
* Selenium for running tests
* Openrefine for data manipulation

The local install also has open ports, so that the services can be accessed without using the reverse proxy
* Wikibase, http://localhost:8080
* WDQS Frontend, http://localhost:8834
* Quickstatements, http://localhost:8840
* OpenRefine, http://localhost:3333
* Grafana, http://localhost:3000
* Reverse proxy (traefik) dashboard: https://localhost/

Note that the containers for local development are set to not restart
so that they do not start automatically when you start your computer.

Some containers are pulled from special MaRDI images:
* wikibase and wikibase_jobrunner are pulled from https://github.com/MaRDI4NFDI/docker-wikibase 
* backup is pulled from https://github.com/MaRDI4NFDI/docker-wikibase/tree/main/backup
* quickstatements is pulled from https://github.com/MaRDI4NFDI/docker-quickstatements

### Notes on the traefik reverse proxy

[traefik](https://doc.traefik.io/traefik/) is an edge router (or reverse
proxy), with the purpose of routing incoming requests to the
corresponding services. E.g., requests to https://portal.mardi4nfdi.de are
forwarded to the wikibase service. Services are discovered and monitored automatically, but
rules to make services accessible on a domain, authentication, redirections,
etc, must be defined in the docker-compose files via docker labels for the
services' containers. See the [docs](https://doc.traefik.io/traefik/).

<!--
### Optionally configure traefik locally

By default, TLD points to localhost. That way one can access the portal at
https://portal.mardi4nfdi.de 
and for example quickstatements via
https://quickstatements.portal.mardi4nfdi.localhost
.
Unfortunately SSL certificates are invalid as letsencrypt obviously cannot provide
local SSL certificates.
-->

<!--

Say we want to access quickstatements, wikibase and the traefik dashboard on our
local installation, via the local domains `quickstatements.local`, `portal.local`
and `traefik.local`. We need to tell traefik to route these addresses to the
services.

1. Add the domains to `/etc/hosts` on Linux systems:

    `127.0.0.1 	portal.local quickstatements.local traefik.local`

2. In `docker-compose.yml`, modify the traefik labels of the quickstatements,
   wikibase and traefik containers to match the local host rules, i.e.,

```yaml
services:
  # ...
  wikibase:
    # ...
    labels:
      - traefik.http.routers.service-wikibase.rule=Host(`portal.local`)
      # ...
  reverse-proxy:
    # ...
    labels:
      - traefik.http.routers.dashboard.rule=Host(`traefik.local`)
      # ...
  quickstatements:
    # ...
    labels:
      - traefik.http.routers.service-quickstatements.rule=Host(`quickstatements.local`)
      # ...
```
3. The traefik dashboard is protected by a password. To disable basic auth,
   comment the label defining the authentication middleware, `-
   traefik.http.routers.dashboard.middlewares=auth`. Alternatively, to test the
   authentication, a local password hash can be generated with  `htpasswd -nb USER
   PASSWORD`. Write the hash in the label `-
   traefik.http.middlewares.auth.basicauth.users=USER:PASSWORD_HASH`, replacing
   all `$` by `$$`. 

4. After starting the containers (see below), the wiki should be accessible on
   https://portal.local, quickstatements on https://quickstatements.local, and
   you can login to the traefik dashboard to check for routing errors at
   https://traefik.local

-->
## Start up the containers

> Be careful, the containers need about 13GB of free memory.

The services are defined in the following three files:
* `docker-compose.yml`: basic stack of containers (wikibase, mysql, elasticsearch, redis, wdqs, quickstatements, traefik, portainer).
* `docker-compose-extra.yml`: additional set of APIs and analytic tools (importer, matomo, goaccess, prometheus, grafana, etc.).
* `docker-compose-dev.yml`: additional parameters for the development environment.

Start up all containers for development:
```
docker-compose -f docker-compose.yml -f docker-compose-extra.yml -f docker-compose-dev.yml up -d
```
Stop the containers:
```
docker-compose -f docker-compose.yml -f docker-compose-extra.yml -f docker-compose-dev.yml down
```

### Tip 1: 
Define the COMPOSE_FILE variable in your `.env` file with a value equal to
`docker-compose.yml:docker-compose-extra.yml:docker-compose-dev.yml`
With this configuration all three files will be automatically read when calling
` docker-compose up -d | down | logs`

### Tip 2: 
Alternatively, if you only want to start the basic containers defined in `docker-compose.yml` you can also
`cp docker-compose-dev.yml docker-compose.override.yml` and modify the new `docker-compose.override.yml` according
to your needs. The `docker-compose.override.yml` will also be automatically picked up when calling
`docker-compose up -d | down | logs` without the need to define COMPOSE_FILE in `.env`.

See [here](https://docs.docker.com/compose/extends/) and [here](https://docs.docker.com/compose/extends/#adding-and-overriding-configuration) for further info.

## Test locally

1. Start up the containers locally as explained above
2. Run the tests: `bash ./run_tests.sh`

## Define images to pull

The images to be pulled are defined at the beginning of the `docker-compose.yml` and `docker-compose-extra.yml`. For instance
```
x-wikibase: &wikibase-image
  ghcr.io/mardi4nfdi/docker-wikibase:main
```
To update a given image to a newer version only the url for the corresponding image needs to be updated. Thus, we make sure that all services based on a given image are using the same image version. This also simplifies reproducibility if we need to rollback to a previous state.

## Deploy on the MaRDI server

After merging a PR the main branch can be deployed to mardi03 [(staging server)](https://staging.portal.mardi4nfdi.org) by manually calling the deployment workflow in srv-mardi03. 

When the main branch is ready for release into mardi02 [(production server)](https://portal.mardi4nfdi.de) a tagged commit must be created.
First run `git tag` to list the latest tags. 
Create and push a new signed tag with the newer version.
```
git tag -s <tag_version>
git push origin <tag_version>
```
The specified <tag_version> can be referenced in the `deploy` script in mardi02. The new tagged version will be deployed automatically after pushing to srv-mardi02.

## Configure Grafana

[Grafana](https://grafana.com/) is a tool to visualize metrics collected by [Prometheus](https://prometheus.io/). Here, Prometheus
is set up to scrape metrics provided by the edge router [traefik](traefik.io), the [backup script](https://github.com/mardi4nfdi/docker-backup), and system metrics of the host
system via [node-exporter](https://github.com/prometheus/node_exporter). The Grafana UI can be accessed via https://grafana.portal.mardi4nfdi.de or https://localhost:3000, locally. The dashboards need to be added manually after initializing the Grafana container in the UI via `Create->Import`:

- backup monitor: import the file [grafana/backup_monitor.json](grafana/backup_monitor.json)
- node-exporter: import e.g. the dashboard id [1860](https://grafana.com/grafana/dashboards/1860)
- traefik: import e.g. the dashboard id [4475](https://grafana.com/grafana/dashboards/4475)

Currently, Grafana does not offer import/export of alerting rules. These have to
be created manually, e.g., for the disk usage of the backup drive and failure of
the backups.

## GoAccess log analyzer

[GoAccess](goaccess.io) is an opensource log analyzer, set up in `docker-compose.yml` to
parse the `access.log` of the reverse proxy traefik.
The resulting report is served with a nginx webserver at
https://stats.portal.mardi4nfdi.de.

We use a custom docker image
[docker-goaccess-cron](https://github.com/MaRDI4NFDI/docker-goaccess-cron), running
goaccess via cron. The configuration contained in `docker-compose.yml` and
`goaccess/goaccess.conf` of
[portal-compose](https://github.com/MaRDI4NFDI/portal-compose) is complete with
exception of the following requirements:

### Logrotation

The size of traefik logs can easily take gigabytes and should be rotated with the unix
tool logrotate (already running on mardi01). A logrotate config for traefik is given by
[traefik/logrotate.conf](https://github.com/MaRDI4NFDI/portal-compose/tree/main/traefik/logrotate.conf)
(should be placed in `/etc/logrotate.d`, requires root; see also the project's server
setup notes and
[man logrotate](https://www.man7.org/linux/man-pages/man8/logrotate.8.html)).
Our goaccess image expects the two log files `access.log` and `access.log.1`, but can
handle the case where the rotated file `log.1` does not exist.

### GeoIP Database

In order to resolve IP geo locations, download the free database `GeoLite2 City` from
[here](https://www.maxmind.com/en/accounts/758058/geoip/downloads).
An account was already registered with our MaRDI4NFDI groupware email account.
Extract the file `GeoLite2-City.mmdb` to the directory `./goaccess/`.

## Develop/debug locally

Create a docker-compose.override.yml like this
```docker-compose
version: '3.4'

services:
  wikibase:
    image: "ghcr.io/mardi4nfdi/docker-wikibase:dev"
    environment:
      XDEBUG_CONFIG: "remote_host=host.docker.internal"
    expose:
      - 9000
    volumes:
      - ./extensions-dev/<extension_to_debug>:/var/www/html/extensions/<extension_to_debug>
      - ./debugging/php.ini:/usr/local/etc/php/php.ini
```
Here `./extensions-dev/<extension_to_debug>` is the path of your local development checkout of the extension, you modify.

For extended documentation on debugging with xdebug, [see](https://portal.mardi4nfdi.de/wiki/Project:DebuggingPHPinMediawiki). 

Eventually, add the docker-compose.override.yml file to your startup command:

Adjust host.docker.internal on linux as [described.](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html#configure-xdebug-wsl)
```bash
docker-compose -f docker-compose.yml -f docker-compose-dev.yml -f docker-compose.override.yml up -d
```
## Build on CI 

The containers will be built and tested automatically by GitHub after each commit on the main branch. The CI steps are defined in `.github/workflows/main.yml`.

Preparations **this has already been done on GitHub**:
* create a [GitHub environment](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment) 
* call it "staging" (specified in .github/workflows/main.yml)
* set (required) these to test passwords, change the default values:
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
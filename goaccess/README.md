# GoAccess setup

[GoAccess](goaccess.io) is an opensource log analyzer, set up in `docker-compose.yml` to
parse the `access.log` of the reverse proxy traefik.
The resulting report is served (here) with a nginx webserver.

We use a custom docker image (https://github.com/MaRDI4NFDI/docker-goaccess-cron), running goaccess via cron.
The configuration contained in `docker-compose.yml` and `goaccess/goaccess.conf` of
[portal-compose](https://github.com/MaRDI4NFDI/portal-compose) is complete with exception of the following requirements:

## Logrotation

The size of traefik logs can easily take gigabytes and should be rotated with the unix
tool logrotate (already running on mardi01). A logrotate config for traefik is given by https://github.com/MaRDI4NFDI/portal-compose/tree/main/traefik/logrotate.conf (should be placed in `/etc/logrotate.d`).
Our goaccess image expects the two log files `access.log` and `access.log.1`, but can
handle the case where the rotated file does not exist.


<!-- ### Mediawiki docker image modifications -->

<!-- - apache2 logs: use commit [90fdd1](https://github.com/MaRDI4NFDI/docker-wikibase/commit/90fdd1562783531691e26f2e1874aa42ea23f311) or newer of our custom mediawiki image [docker-wikibase](https://github.com/MaRDI4NFDI/docker-wikibase). -->

<!--   Reason: The official mediawiki docker image is based on -->
<!--   [php:7.4-apache](https://hub.docker.com/layers/php/library/php/7.4-apache/images/sha256-f2e8c86002a794426a68537dc772c680865065da4127d3824f738e11bd4663af?context=explore), -->
<!--   which symlinks the log file to `/dev/stdout`, such that the logs are accessible with -->
<!--   `docker logs`. [docker-wikibase](https://github.com/MaRDI4NFDI/docker-wikibase) as of commit [90fdd1](https://github.com/MaRDI4NFDI/docker-wikibase/commit/90fdd1562783531691e26f2e1874aa42ea23f311) replaces these symlinks by real files and -->
<!--   tails them in order to still achieve logging with docker. -->

## GeoIP Database

In order to resolve IP geo locations, download the free database `GeoLite2 City` from https://www.maxmind.com/en/accounts/758058/geoip/downloads.
An account was already registered with our MaRDI4NFDI groupware email account.
Extract the file `GeoLite2-City.mmdb` to the directory `./goaccess/`.
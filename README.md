# portal-compose
docker-composer repo for mardi

## Installation
``
git clone --recurse-submodules git@github.com:MaRDI4NFDI/portal-compose.git
copy ./mediawiki/template.env to ./.env
``

Change parameters for your local installation in .env if required, this file will not be committed.

Change wiki settings in mediawiki/LocalSettings.d/LocalSettings.override.php. This file will be committed.

## Test
Start-up the containers
`docker-compose up -d`

Run the tests
`bash ./run_tests.sh`

## More
See [Discussion in the project's wiki](https://github.com/MaRDI4NFDI/portal-compose/wiki)

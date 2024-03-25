<?php
$wgExternalDataSources['MSC'] = [
    'server' => getenv('DB_SERVER'),
    'type' => 'mysql',
    'name' => 'my_wiki',
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'prepared' => <<<'SQL'
SELECT msc_string
FROM msc_id_mapping
WHERE msc_id = ?
SQL,
    'types' => 's'
];
<?php
$wgExternalDataSources['AbstractDB'] = [
    'server' => getenv('DB_SERVER'),
    'type' => 'mysql',
    'name' => 'paper_abstracts_db',
    'user' => getenv('MSC_USER'),
    'password' => getenv('MSC_PASS'),
    'prepared' => <<<'SQL'
SELECT abstract, abstract_source, summary, summary_source
FROM paper_abstracts
WHERE paper_qid = ?
SQL,
    'types' => 's'
];
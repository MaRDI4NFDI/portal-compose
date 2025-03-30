<?php

use MediaWiki\Logger\LoggerFactory;

$wgDBname = 'my_wiki';
if ( defined( 'MW_DB' ) ) {
	// Set $wikiId from the defined constant 'MW_DB' that is set by maintenance scripts.
	$wgDBname = MW_DB;
} elseif ( !isset( $_SERVER['HTTP_HOST'] ) ) {
	$logger = LoggerFactory::getInstance( 'MaRDIconf' );
	$logger->warning( 'Server name not set. Falling back to my_wiki.' );
} elseif ( str_contains( $_SERVER['HTTP_HOST'], 'swmath' ) ) {
	$wgDBname = 'wiki_swmath';
} elseif ( str_contains( $_SERVER['HTTP_HOST'], 'en.wiki' ) ) {
	$wgDBname = 'enwiki';
}

if ( false && getenv( 'CI' ) !== 'true' ) {
	$wgLBFactoryConf = [

	'class' => 'LBFactoryMulti',

	'sectionsByDB' => [
		'my_wiki' => 's1',
		'wiki_swmath' => 's1',
	],

	'sectionLoads' => [
		's1' => [
			'mysql.svc'  => 0,
			'mysql-repl.svc'  => 50, /* the 50 is the weight (of replica servers). Would matter if you had multiple */
		],
	],

	'serverTemplate' => [
		'dbname'      => $wgDBname,
		'user'          => $wgDBuser,
		'password'      => $wgDBpassword,
		'type'          => 'mysql',
		'flags'          => DBO_DEFAULT,
		'max lag'      => 30,
	],
	];
}

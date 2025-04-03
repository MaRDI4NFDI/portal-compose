<?php

use MediaWiki\Logger\LoggerFactory;

$wgDBname = 'my_wiki';
$host = $_SERVER['HTTP_HOST'] ?? false;
if ( defined( 'MW_DB' ) ) {
	// Set $wikiId from the defined constant 'MW_DB' that is set by maintenance scripts.
	$wgDBname = MW_DB;
} elseif ( $host === false ) {
	$logger = LoggerFactory::getInstance( 'MaRDIconf' );
	$logger->warning( 'Server name not set. Falling back to my_wiki.' );
} elseif ( str_contains( $host, 'swmath' ) ) {
	$wgDBname = 'wiki_swmath';
} elseif ( str_contains( $host, '.wik' ) ) {
	$wikibase_host = getenv( 'WIKIBASE_HOST' );
	if ( preg_match( '/^([0-9a-z-]+)\.(wik.*?)' . $wikibase_host . '$/', $host, $match ) !== 1 ) {
		die( "Server name $host does not match the patterns for wikis." );
	}
	$lang = str_replace( '-', '_', $match[1] );
	$wgDBname = $lang . $match[2];
}

/** Set language code */
if ( preg_match( '/^([a-z_]+)(wik.*?)$/', $wgDBname, $match ) === 1 ) {
	$lang = str_replace( '_', '-', $match[1] );
	if ( LanguageCode::isWellFormedLanguageTag( $lang ) ) {
		$wgLanguageCode = $lang;
	}
	// fall back to english otherwise
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

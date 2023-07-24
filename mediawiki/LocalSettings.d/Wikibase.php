<?php
## Wikibase

wfLoadExtension( 'WikibaseClient', "$IP/extensions/Wikibase/extension-client.json" );
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

# enable linking between wikibase and content pages
$wgWBRepoSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteGlobalID'] = 'mardi';
$wgWBClientSettings['repoUrl'] = 'https://portal.mardi4nfdi.de';
$wgWBClientSettings['repoScriptPath'] = '/w';
$wgWBClientSettings['repoArticlePath'] = '/wiki/$1';
$wgWBClientSettings['entitySources'] = [
		'mardi_source' => [
				'repoDatabase' => 'my_wiki',
				'baseUri' => 'https://portal.mardi4nfdi.de/entity',
				'entityNamespaces' => [
						'item' => 120,
						'property' => 122,
				],
				'rdfNodeNamespacePrefix' => 'wd',
				'rdfPredicateNamespacePrefix' => '',
				'interwikiPrefix' => 'mardi',
		],
];
$wgWBClientSettings['itemAndPropertySourceName'] = 'mardi_source';
// my_wiki is the MaRDI database
$wgLocalDatabases = [ 'wiki_swmath', 'my_wiki' ];

if ( $wgDBname !== 'wiki_swmath' ){

	wfLoadExtension( 'WikibaseRepository', "$IP/extensions/Wikibase/extension-repo.json" );
	require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";

	$wgWBRepoSettings['localClientDatabases'] = [
		'mardi' => 'my_wiki',
		'swmath' => 'wiki_swmath'
	];
	// insert site with
	// php addSite.php --filepath=https://portal.mardi4nfdi.de/w/\$1 --pagepath=https://portal.mardi4nfdi.de/wiki/\$1 --language en --interwiki-id mardi mardi mathematics
	// php addSite.php --filepath=https://staging.swmath.org/w/\$1 --pagepath=https://staging.swmath.org/wiki/\$1 --language en --interwiki-id swmath swmath mathematics


	# Pingback
	$wgWBRepoSettings['wikibasePingback'] = false;

	# Increase string size limits
	$wgWBRepoSettings['string-limits'] = [
		'VT:string' => [
			'length' => 200000,
		],
		'multilang' => [
			'length' => 2000,
		],
		'VT:monolingualtext' => [
			'length' => 1000,
		],
	];
}
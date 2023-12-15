<?php
## Wikibase

wfLoadExtension( 'WikibaseClient', "$IP/extensions/Wikibase/extension-client.json" );
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

$wikibaseHost = getenv('WIKIBASE_HOST');

if ($wikibaseHost === 'localhost') {
    $portalHost = getenv('WIKIBASE_SCHEME') . '://localhost:' . getenv('WIKIBASE_PORT');
} else {
    $portalHost = getenv('WIKIBASE_SCHEME') . '://'. $wikibaseHost;
}

# enable linking between wikibase and content pages
$wgWBRepoSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteGlobalID'] = 'mardi';
$wgWBClientSettings['repoUrl'] = $portalHost;
$wgWBClientSettings['repoScriptPath'] = '/w';
$wgWBClientSettings['repoArticlePath'] = '/wiki/$1';
$wgWBClientSettings['entitySources'] = [
		'mardi_source' => [
				'repoDatabase' => 'my_wiki',
				'baseUri' => $portalHost . '/entity',
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

// https://github.com/MaRDI4NFDI/portal-compose/issues/224
$wgNamespacesToBeSearchedDefault[122] = true; // WB_PROPERTY_NAMESPACE===122

if ( $wgDBname !== 'wiki_swmath' ){

	wfLoadExtension( 'WikibaseRepository', "$IP/extensions/Wikibase/extension-repo.json" );
	// from https://github.com/wikimedia/mediawiki-extensions-Wikibase/blob/f2bd35609b6bf3f8d38ef8c78d2f340497906706/repo/includes/RepoHooks.php#L170C1-L180C61
	$wgExtraNamespaces[120] = 'Item';
	$wgExtraNamespaces[121] = 'Item_talk';
	$wgExtraNamespaces[122] = 'Property';
	$wgExtraNamespaces[123] = 'Property_talk';
	// do not declare namespaces if that would be done by default https://gerrit.wikimedia.org/r/c/mediawiki/extensions/Wikibase/+/933906 https://phabricator.wikimedia.org/T291617
	$wgWBRepoSettings['defaultEntityNamespaces'] = false;
	$wgWBRepoSettings['entitySources'] = [
			'mardi_source' => [
				'repoDatabase' => 'my_wiki',
				'baseUri' => $portalHost . '/entity',
				'entityNamespaces' => [
						'item' => 120,
						'property' => 122,
				],
				'rdfNodeNamespacePrefix' => 'wd',
				'rdfPredicateNamespacePrefix' => '',
				'interwikiPrefix' => 'mardi',
		],
	];
	$wgWBRepoSettings['localEntitySourceName'] = 'mardi_source';
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

<?php

// https://www.mediawiki.org/wiki/Extension:CirrusSearch
wfLoadExtension( 'CirrusSearch' );
// enable insource queries https://www.mediawiki.org/wiki/Extension:CirrusSearch#Enable_regex_queries
$wgCirrusSearchWikimediaExtraPlugin[ 'regex' ] = [ 'build', 'use', 'max_inspect' => 10000 ];
// https://www.mediawiki.org/wiki/Extension:AdvancedSearch
wfLoadExtension( 'AdvancedSearch' );

// See WikibaseCirrusSearch.php for further configuration

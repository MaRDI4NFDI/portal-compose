<?php
if ( str_contains($_SERVER['HTTP_HOST'],'swmath') ){
  $wgDBname = 'wiki_swmath';
  # Set name of the wiki
  $wgSitename = 'swMATH staging';
  # Set swMATH logo
  $wgLogos = [
    'wordmark' => [
        'src' => $wgScriptPath . '/images_repo/swMATH.svg',	// path to wordmark version
        'width' => 155,
        'height' => 35
    	]
    ];
  # Load swMATH specific extensions
  # wfLoadExtension( 'ExternalData' );
}

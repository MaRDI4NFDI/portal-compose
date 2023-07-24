<?php
if ( $wgDBname === 'wiki_swmath' ){
  $wgSitename = 'swMATH staging';
  # Set swMATH logo
  $wgLogos = [
    'wordmark' => [
        'src' => $wgScriptPath . '/images_repo/swMATH.svg',	// path to wordmark version
        'width' => 155,
        'height' => 35
    	]
    ];
  $wgWBClientSettings['siteGlobalID'] = 'swmath';

  # Load swMATH specific extensions
  # wfLoadExtension( 'ExternalData' );
}

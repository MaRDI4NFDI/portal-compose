<?php
if ( str_contains($_SERVER['HTTP_HOST'],'swmath') ){
  $wgDBname = 'wiki_swmath';
  # Set name of the wiki
  $wgSitename = 'swMATH staging';
  # Set swMATH logo and icon (also SVG?)
  $wgLogos = [
    'icon' => $wgScriptPath . '/images_repo/swMATH.svg',
    'svg' => $wgScriptPath . '/images_repo/swMATH.svg',
  ];
  # Load swMATH specific extensions
  # wfLoadExtension( 'ExternalData' );
}

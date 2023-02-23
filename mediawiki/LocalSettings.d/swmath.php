<?php
if ( str_contains($_SERVER['SERVER_NAME'],'swmath') ){
  $wgDBname = 'wiki_swmath';
  $wgLogos = false;
  # Load swMATH specifi extensions
  wfLoadExtension( 'ExternalData' );
  wfLoadExtension( 'UrlGetParameters' );
}

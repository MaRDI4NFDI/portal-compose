<?php
if ( str_contains($_SERVER['HTTP_HOST'],'swmath') ){
  $wgDBname = 'wiki_swmath';
  $wgLogos = false;
  # Load swMATH specific extensions
  # wfLoadExtension( 'ExternalData' );
}

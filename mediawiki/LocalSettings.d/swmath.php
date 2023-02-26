<?php
if ( str_contains($_SERVER['SERVER_NAME'],'swmath') ){
  $wgDBname = 'wiki_swmath';
  $wgLogos = false;
  # Load swMATH specifi extensions
  # wfLoadExtension( 'ExternalData' );
  # The extension does not allow loading ExtneralData explicitly
  # It was attempted to load External Data twice, from /var/www/html/extensions/ExternalData/extension.json and /var/www/html/extensions/UrlGetParameters/extension.json.
  wfLoadExtension( 'UrlGetParameters' );
}

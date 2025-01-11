<?php

wfLoadExtension( 'Widgets' );
// see https://www.mediawiki.org/wiki/Extension:Widgets#Configuration

$wgGroupPermissions['bureaucrat']['editwidgets'] = true;
   
$wgWidgetsCompileDir = "/var/www/html/w/images";

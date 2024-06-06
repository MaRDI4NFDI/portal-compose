<?php

wfLoadExtension( 'Widgets' );
// see https://www.mediawiki.org/wiki/Extension:Widgets#Configuration

$wgGroupPermissions['bureaucrat']['editwidgets'] = true;
   
$wgWidgetsCompileDir = "/shared/compiled_widget_templates/";

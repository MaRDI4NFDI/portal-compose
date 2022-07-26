<?php
# https://www.mediawiki.org/wiki/Extension:PluggableAuth
wfLoadExtension( 'PluggableAuth' );

$wgPluggableAuth_Config = array(
    array(
        'plugin' => 'Shibboleth',
    ),
);

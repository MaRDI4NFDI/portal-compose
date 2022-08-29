<?php
# https://www.mediawiki.org/wiki/Extension:PluggableAuth
wfLoadExtension( 'PluggableAuth' );

$wgPluggableAuth_Config = [
    [
        'plugin' => 'Shibboleth',
    ]
];

<?php
# https://www.mediawiki.org/wiki/Extension:PluggableAuth

$wgPluggableAuth_EnableLocalLogin=true;
wfLoadExtension( 'PluggableAuth' );

$wgPluggableAuth_Config = [
    [
        'plugin' => 'Shibboleth',
    ]
];

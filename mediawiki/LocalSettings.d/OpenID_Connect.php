<?php
wfLoadExtension( 'OpenIDConnect' );
$wgPluggableAuth_Config[] = [
    'plugin' => 'OpenIDConnect',
    'data' => [
        'providerURL' => 'https://accounts.google.com',
        'clientID' => '816491008307-88db64n43qenpm8finv9f94p4j33jj9t.apps.googleusercontent.com',
        'clientsecret' => $_ENV['GOOGLE_OPENID_SECRET'],
    ]
];

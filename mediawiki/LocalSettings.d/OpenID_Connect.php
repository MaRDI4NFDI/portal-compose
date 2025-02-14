<?php
wfLoadExtension( 'OpenIDConnect' );
/** $wgPluggableAuth_Config['Login with your Google Account'] = [
    'plugin' => 'OpenIDConnect',
    'data' => [
        'providerURL' => 'https://accounts.google.com',
        'clientID' => '816491008307-88db64n43qenpm8finv9f94p4j33jj9t.apps.googleusercontent.com',
        'clientsecret' => $_ENV['GOOGLE_OPENID_SECRET'],
    ]
];*/
$wgPluggableAuth_Config['Login with your NFDI AAI Account'] = [
    'plugin' => 'OpenIDConnect',
    'data' => [
        'providerURL' => 'https://auth.didmos.nfdi-aai.de',
        'clientID' => 'u2pwxwERLrACYw3z',
        'clientsecret' => $_ENV['NFDI_AAI_SECRET'],
        'scope' => [],
    ]
];
$wgOpenIDConnect_MigrateUsersByEmail=true;

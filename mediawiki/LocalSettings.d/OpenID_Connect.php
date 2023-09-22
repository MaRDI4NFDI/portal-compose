<?php
wfLoadExtension( 'OpenIDConnect' );
$wgPluggableAuth_Config['Login with your Google Account'] = [
    'plugin' => 'OpenIDConnect',
    'data' => [
        'providerURL' => 'https://accounts.google.com',
        'clientID' => '816491008307-88db64n43qenpm8finv9f94p4j33jj9t.apps.googleusercontent.com',
        'clientsecret' => $_ENV['GOOGLE_OPENID_SECRET'],
    ]
];
$wgPluggableAuth_Config['Login with your GitHub Account'] = [
    'plugin' => 'OpenIDConnect',
    'data' => [
        'providerURL' => 'https://github.com',
        'clientID' => '37d22a323b1de98272a8',
        'clientsecret' => $_ENV['GITHUB_OPENID_SECRET'],
    ]
];
$wgOpenIDConnect_MigrateUsersByEmail=true;

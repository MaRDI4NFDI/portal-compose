<?php
$wgSMTP = [
    'host' => 'ssl://smtp.gmail.com',  // hostname of the email server
    'IDHost' => 'portal.MaRDI4nfdi.de',
    'port' => 465,
    'auth' => true
    // username and password are missing here on purpose, they'll be added with private config overwrite
];

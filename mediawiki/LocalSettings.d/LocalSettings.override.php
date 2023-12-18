<?php

/*******************************/
/* Enable Federated properties */
/*******************************/
#$wgWBRepoSettings['federatedPropertiesEnabled'] = true;

/*******************************/
/* Enables ConfirmEdit Captcha */
/*******************************/
#wfLoadExtension( 'ConfirmEdit/QuestyCaptcha' );
#$wgCaptchaQuestions = [
#  'What animal' => 'dog',
#];

#$wgCaptchaTriggers['edit']          = true;
#$wgCaptchaTriggers['create']        = true;
#$wgCaptchaTriggers['createtalk']    = true;
#$wgCaptchaTriggers['addurl']        = true;
#$wgCaptchaTriggers['createaccount'] = true;
#$wgCaptchaTriggers['badlogin']      = true;

/*******************************/
/* Disable UI error-reporting  */
/*******************************/
#ini_set( 'display_errors', 0 );

# Prevent new user registrations except by sysops
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['user']['createaccount'] = true;

# Restrict anonymous editing
$wgGroupPermissions['*']['edit'] = false;

# Remove rate limits for bots
$wgGroupPermissions['bot']['noratelimit'] = true;



# Enabling uploads for images.
$wgEnableUploads = true;
$wgFileExtensions[] = 'svg';
# Explicitly mentioning the upload-path for image-upload.
$wgUploadPath = $wgScriptPath . '/images/';
# Enable SVG converter
$wgSVGConverter = 'rsvg';

$wgWBRepoSettings['formatterUrlProperty']='P10';

$wgWBRepoSettings['allowEntityImport'] = true;
$wgShowExceptionDetails = true;

# Settings for lockdown extension (private documentation)

## Defining constants for additional namespaces.
define("NS_PRIVATE", 3000); // This MUST be even.

## Adding additional namespaces.
$wgExtraNamespaces[NS_PRIVATE] = "Private";

## Adding new user group private which is blocking reading and editing pages in private namespace.
$wgGroupPermissions['private'] = [];
$wgNamespacePermissionLockdown[NS_PRIVATE]['edit'] = [ 'private' ];
$wgNamespacePermissionLockdown[NS_PRIVATE]['read'] = [ 'private' ];

#
# increase memory limit
ini_set('memory_limit', '2G');


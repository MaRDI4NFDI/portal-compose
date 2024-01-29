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
# Allow account creation for people that have NFDI Accounts
$wgGroupPermissions['*']['autocreateaccount'] = true;


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

# Extensions required by templates
wfLoadExtension( 'TemplateStyles' );
wfLoadExtension( 'JsonConfig' );
wfLoadExtension( 'InputBox' );
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'Math' );
# collect information on errors during profile page creation
$wgDebugLogGroups['MathSearch'] = array(
	'destination' => '/var/log/mediawiki/MathSearch.log',
	'level' => 'info',
);
wfLoadExtension( 'MathSearch' );
wfLoadExtension( 'Lockdown' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'ExternalContent' );

$wgWBRepoSettings['formatterUrlProperty']='P10';
$wgMathDisableTexFilter = 'always';

$wgMathLaTeXMLUrl = 'http://latexml:8080/convert/';
#overwrite settings
$wgMathDefaultLaTeXMLSetting = array(
        'format' => 'xhtml',
        'whatsin' => 'math',
        'whatsout' => 'math',
        'pmml',
        'cmml',
        'mathtex',
        'nodefaultresources',
        'preload' => array(
                'LaTeX.pool',
                'article.cls',
                'amsmath.sty',
                'amsthm.sty',
                'amstext.sty',
                'amssymb.sty',
                'eucal.sty',
                // '[dvipsnames]xcolor.sty',
                'url.sty',
                'hyperref.sty',
                '[ids]latexml.sty',
                'DLMFmath.sty',
                'DRMFfcns.sty',
                'DLMFsupport.sty.ltxml',
        ),
        'linelength' => 90,
);

$wgWBRepoSettings['allowEntityImport'] = true;
$wgShowExceptionDetails = true;
$wgVisualEditorAvailableNamespaces = [
    'Project' => true,
    'Private' => true,
];


# Settings for lockdown extension (private documentation)

## Defining constants for additional namespaces.
define("NS_PRIVATE", 3000); // This MUST be even.

## Adding additional namespaces.
$wgExtraNamespaces[NS_PRIVATE] = "Private";

## Adding new user group private which is blocking reading and editing pages in private namespace.
$wgGroupPermissions['private'] = [];
$wgNamespacePermissionLockdown[NS_PRIVATE]['edit'] = [ 'private' ];
$wgNamespacePermissionLockdown[NS_PRIVATE]['read'] = [ 'private' ];

# Settings for MathSearch extension.
$wgMathSearchBaseXBackendUrl="http://formulasearch:1985/basex/";

# Settings for Math-Extension
$wgMathFullRestbaseURL = 'https://wikimedia.org/api/rest_';
$wgMathMathMLUrl = 'https://mathoid-beta.wmflabs.org';
// enable math native rendering (experimental)
$wgMathValidModes[] =  'native'; 


#popups for math
$wgMathWikibasePropertyIdDefiningFormula = "P14";
$wgMathWikibasePropertyIdHasPart = "P4";

#
# increase memory limit
ini_set('memory_limit', '2G');

# https://github.com/MaRDI4NFDI/portal-compose/issues/322
$wgUseInstantCommons = true; 

# https://github.com/MaRDI4NFDI/portal-compose/issues/419
$wgJobTypeConf['default'] = [
    'class'          => 'JobQueueRedis',
    'redisServer'    => 'redis:6379', // this is the host ip from the default network
    'redisConfig'    => [],
    'daemonized'     => true
 ];
# Allow to display how many profie pages exist https://www.mediawiki.org/wiki/Help:Magic_words#Statistics
$wgAllowSlowParserFunctions=true;

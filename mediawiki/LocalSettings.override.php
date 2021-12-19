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
#ini_set( 'display_errors', 1 );

# Prevent new user registrations except by sysops
$wgGroupPermissions['*']['createaccount'] = false;

# Restrict anonymous editing
$wgGroupPermissions['*']['edit'] = false;

# Set name of the wiki
$wgSitename = 'MaRDI portal';

# Set MaRDI logo and icon
$wgLogo = $wgScriptPath . '/images/MaRDI_Logo.png';
$wgFavicon = $wgScriptPath . "/images/favicon.png";

# Footer
$wgHooks['SkinTemplateOutputPageBeforeExec'][] = function( $sk, &$tpl ) {
        # Remove existing entries
        $tpl->set('about', FALSE);
        $tpl->set('privacy', FALSE);
        $tpl->set('disclaimer', FALSE);
        return true;
};
$wgHooks['SkinAddFooterLinks'][] = function ( Skin $skin, string $key, array &$footerlinks ) {
    if ( $key === 'places' ) {
        $footerlinks['Imprint'] = Html::element( 'a',
        [
            'href' => 'https://www.mardi4nfdi.de/imprint',
            'rel' => 'noreferrer noopener' // not required, but recommended for security reasons
        ], 
        'Imprint');
    };
    return true;
};

# Extensions required by templates
wfLoadExtension( 'TemplateStyles' );
wfLoadExtension( 'JsonConfig' );
wfLoadExtension( 'InputBox' );
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'Math' );
wfLoadExtension( 'MathSearch' );

$wgWBRepoSettings['formatterUrlProperty']='P10';
$wgMathDisableTexFilter = 'always';

# Temporary workaround for missing DLMF macos on wuppertal account
$wgMathLaTeXMLUrl = 'https://drmf-latexml.wmflabs.org/convert/';

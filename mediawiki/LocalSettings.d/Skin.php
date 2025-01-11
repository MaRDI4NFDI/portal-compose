<?php

wfLoadSkin( 'Vector' );
$wgDefaultSkin = 'vector-2022';

# Set name of the wiki
$wgSitename = 'MaRDI portal';

# Set MaRDI logo and icon
$wgLogos = [
	'icon' => $wgScriptPath . '/images_repo/MaRDI_Logo_L_5_rgb_50p.svg',
	'svg' => $wgScriptPath . '/images_repo/MaRDI_Logo_L_5_rgb_50p.svg',
];
$wgFavicon = $wgScriptPath . '/images_repo/favicon.png';

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
# https://github.com/ProfessionalWiki/MardiSkin
wfLoadExtension( 'Bootstrap' );
wfLoadskin( 'chameleon' );
$egChameleonLayoutFile= '/var/www/html/w/skins/MardiSkin/layout.xml';
$egChameleonExternalStyleModules = [
	'/var/www/html/w/skins/MardiSkin/variables.scss' => 'beforeVariables',
	'/var/www/html/w/skins/MardiSkin/styles.scss' => 'afterMain',
];
if ( $wgDBname === 'my_wiki' ){
	$wgDefaultSkin = 'chameleon';
}

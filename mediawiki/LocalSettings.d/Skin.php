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

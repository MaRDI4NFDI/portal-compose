<?php
# https://www.mediawiki.org/wiki/Extension:Plausible
wfLoadExtension( 'Plausible' );
$wgPlausibleDomain = "https://plausible.io";
$wgPlausibleDomainKey = "portal.mardi4nfdi.de";

# configuration
$wgPlausibleHonorDNT = false;
$wgPlausibleTrackOutboundLinks  = true;
$wgPlausibleTrackLoggedIn  = true;

# tracking scripts
$wgPlausibleTrack404 = true;
$wgPlausibleTrackSearchInput = true;
$wgPlausibleTrackEditButtonClicks = true;

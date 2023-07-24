<?php

// https://www.mediawiki.org/wiki/Extension:WikibaseManifest

## WikibaseManifest Configuration
$wgDBname !== 'wiki_swmath' ? wfLoadExtension( 'WikibaseManifest' ) : NULL;
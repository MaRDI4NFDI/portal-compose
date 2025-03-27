<?php

# Enable Changing the title for persons.
$wgRestrictDisplayTitle=false;
# The Display Title extension allows a page's display title to be used as the default link text in links to the page - both links from other pages as well as self-links on the page.
# https://www.mediawiki.org/wiki/Extension:Display_Title
wfLoadExtension( 'DisplayTitle' );
// Replace display_title with title fields otherwise keep the defaults from https://gerrit.wikimedia.org/g/mediawiki/extensions/CirrusSearch/%2B/HEAD/docs/settings.txt
$wgCirrusSearchWeights = [
    'title' => 20,
    'redirect' => 15,
    'category' => 8,
    'heading' => 5,
    'opening_text' => 3,
    'text' => 1,
    'auxiliary_text' => 0.5,
    'file_text' => 0.5,
];

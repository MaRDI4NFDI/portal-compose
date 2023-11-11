<?php

# Enable Changing the title for persons.
$wgRestrictDisplayTitle=false;
# The Display Title extension allows a page's display title to be used as the default link text in links to the page - both links from other pages as well as self-links on the page.
# https://www.mediawiki.org/wiki/Extension:Display_Title
wfLoadExtension( 'DisplayTitle' );
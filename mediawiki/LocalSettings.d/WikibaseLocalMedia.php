<?php

// https://github.com/ProfessionalWiki/WikibaseLocalMedia
## WikibaseLocalMedia Configuration
## NOTE: WikibaseLocalMedia does currently not work in a client only setup.
$wgDBname !== 'wiki_swmath' ?  wfLoadExtension( 'WikibaseLocalMedia' ) : NULL;
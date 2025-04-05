<?php
if ( preg_match( '/(test2wiki|sourceswiki|wikisource)$/', $wgDBname, $match ) === 1 ) {
  wfLoadExtension( 'ProofreadPage' );
}

<?php
if ( preg_match( '/wikisource$/', $wgDBname, $match ) === 1 ) {
  wfLoadExtension( 'ProofreadPage' );
}

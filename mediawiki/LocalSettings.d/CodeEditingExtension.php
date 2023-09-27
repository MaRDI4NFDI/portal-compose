<?php
wfLoadExtension( 'JsonConfig' );
wfLoadExtension( 'CodeEditor' );
$wgDefaultUserOptions['usebetatoolbar'] = 1; // user option provided by WikiEditor extension
wfLoadExtension( 'CodeMirror' );
// Enables use of CodeMirror by default but still allow users to disable it
$wgDefaultUserOptions['usecodemirror'] = 1;

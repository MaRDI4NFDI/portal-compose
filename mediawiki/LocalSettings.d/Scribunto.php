<?php
## Scribunto 
wfLoadExtension( 'Scribunto' );
$wgScribuntoDefaultEngine = 'luastandalone';
$wgScribuntoEngineConf['luastandalone']['memoryLimit'] = 209715200; # bytes

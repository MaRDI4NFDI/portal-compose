<?php
# Needs to be loaded after NS_CODEMETA is defined
wfLoadExtension( 'JsonConfig' );
// $wgJsonConfigEnableLuaSupport = true; // required to use JsonConfig in Lua
// Content model is 'JsonConfig.CodeMeta'
// Model class is set to NULL to allow non-validated data
$wgJsonConfigModels['JsonConfig.CodeMeta'] = null;
$wgJsonConfigs['JsonConfig.CodeMeta'] = [ 
        'namespace' => NS_CODEMETA, 
        'nsName' => 'CodeMeta',
        'pattern' => '/.\.json/',
];
wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'CodeEditor' );
$wgDefaultUserOptions['usebetatoolbar'] = 1; // user option provided by WikiEditor extension
wfLoadExtension( 'CodeMirror' );
// Enables use of CodeMirror by default but still allow users to disable it
$wgDefaultUserOptions['usecodemirror'] = 1;

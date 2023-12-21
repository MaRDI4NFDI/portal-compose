<?php
$wgReadOnly = 'This wiki is currently being upgraded to a newer software version. Please check back in a couple of hours.';
$wgDBname =  str_contains($_SERVER['HTTP_HOST']??'','swmath') ? 'wiki_swmath' : 'my_wiki';
if ( false && getenv('CI') !== 'true' ) {
    $wgLBFactoryConf = array(
    
    'class' => 'LBFactoryMulti',
    
    'sectionsByDB' => array(
        'my_wiki' => 's1', 
        'wiki_swmath' => 's1',
    ),
    
    'sectionLoads' => array(
        's1' => array(
            'mysql.svc'  => 0,
            'mysql-repl.svc'  => 50, /* the 50 is the weight (of replica servers). Would matter if you had multiple */
        ),
    ),
    
    
    'serverTemplate' => array(
        'dbname'      => $wgDBname,
        'user'          => $wgDBuser,
        'password'      => $wgDBpassword,
        'type'          => 'mysql',
        'flags'          => DBO_DEFAULT,
        'max lag'      => 30,
    ),
    );
}

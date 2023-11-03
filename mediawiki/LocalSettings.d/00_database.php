<?php
$wgReadOnly = 'This wiki is currently being upgraded to a newer software version. Please check back in a couple of hours.';
$wgDBname =  str_contains($_SERVER['HTTP_HOST']??'','swmath') ? 'wiki_swmath' : 'my_wiki';

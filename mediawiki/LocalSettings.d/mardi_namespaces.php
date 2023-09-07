<?php
// Define constants for my additional namespaces.
define("NS_FORMULA", 4200); // This MUST be even.
define("NS_FORMULA_TALK", 4201); // This MUST be the following odd integer.
define("NS_PERSON", 4202);
define("NS_PERSON_TALK", 4203);

// Add namespaces.
$wgExtraNamespaces[NS_FORMULA] = "Formula";
$wgExtraNamespaces[NS_FORMULA_TALK] = "Formula_talk";
$wgExtraNamespaces[NS_PERSON] = "Person";
$wgExtraNamespaces[NS_PERSON_TALK] = "Person_talk";

$wgNamespaceProtection[NS_FORMULA] = array( 'overwriteprofilepages' ); 
$wgNamespaceProtection[NS_PERSON] = array( 'overwriteprofilepages' ); 

$wgGroupPermissions['sysop']['overwriteprofilepages'] = true;

$wgContentNamespaces[] = NS_FORMULA;
$wgContentNamespaces[] = NS_PERSON;


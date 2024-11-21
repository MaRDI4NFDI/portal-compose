<?php
// Define constants for my additional namespaces.
define("NS_FORMULA", 4200); // This MUST be even.
define("NS_FORMULA_TALK", 4201); // This MUST be the following odd integer.
define("NS_PERSON", 4202);
define("NS_PERSON_TALK", 4203);
define("NS_CODEMETA", 4204);
define("NS_CODEMETA_TALK", 4205);
define("NS_PUBLICATION", 4206);
define("NS_PUBLICATION_TALK", 4207);
define("NS_SOFTWARE", 4208);
define("NS_SOFTWARE_TALK", 4209);
define("NS_DATASET", 4210);
define("NS_DATASET_TALK", 4211);
define("NS_COMMUNITY", 4212);
define("NS_COMMUNITY_TALK", 4213);
define("NS_WORKFLOW", 4214);
define("NS_WORKFLOW_TALK", 4215);
define("NS_ALGORITHM", 4216);
define("NS_ALGORITHM_TALK", 4217);
define("NS_SERVICE", 4218);
define("NS_SERVICE_TALK", 4219);

// Add namespaces.
$wgExtraNamespaces[NS_FORMULA] = "Formula";
$wgExtraNamespaces[NS_FORMULA_TALK] = "Formula_talk";
$wgExtraNamespaces[NS_PERSON] = "Person";
$wgExtraNamespaces[NS_PERSON_TALK] = "Person_talk";
$wgExtraNamespaces[NS_PUBLICATION] = "Publication";
$wgExtraNamespaces[NS_PUBLICATION_TALK] = "Publication_talk";
$wgExtraNamespaces[NS_SOFTWARE] = "Software";
$wgExtraNamespaces[NS_SOFTWARE_TALK] = "Software_talk";
$wgExtraNamespaces[NS_DATASET] = "Dataset";
$wgExtraNamespaces[NS_DATASET_TALK] = "Dataset_talk";
$wgExtraNamespaces[NS_COMMUNITY] = "Community";
$wgExtraNamespaces[NS_COMMUNITY_TALK] = "Community_talk";
$wgExtraNamespaces[NS_WORKFLOW] = "Workflow";
$wgExtraNamespaces[NS_WORKFLOW_TALK] = "Workflow_talk";
$wgExtraNamespaces[NS_ALGORITHM] = "Algorithm";
$wgExtraNamespaces[NS_ALGORITHM_TALK] = "Algorithm_talk";
$wgExtraNamespaces[NS_SERVICE] = "Service";
$wgExtraNamespaces[NS_SERVICE_TALK] = "Service_talk";

$wgNamespaceProtection[NS_FORMULA] = array( 'overwriteprofilepages' ); 
$wgNamespaceProtection[NS_PERSON] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_PUBLICATION] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_SOFTWARE] = array( 'overwriteprofilepages' ); 
$wgNamespaceProtection[NS_DATASET] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_COMMUNITY] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_WORKFLOW] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_ALGORITHM] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_SERVICE] = array( 'overwriteprofilepages' );


$wgGroupPermissions['sysop']['overwriteprofilepages'] = true;

$wgContentNamespaces[] = NS_FORMULA;
$wgContentNamespaces[] = NS_PERSON;
$wgContentNamespaces[] = NS_PUBLICATION;
$wgContentNamespaces[] = NS_SOFTWARE;
$wgContentNamespaces[] = NS_DATASET;
$wgContentNamespaces[] = NS_COMMUNITY;
$wgContentNamespaces[] = NS_WORKFLOW;
$wgContentNamespaces[] = NS_ALGORITHM;
$wgContentNamespaces[] = NS_SERVICE;



$wgNamespacesToBeSearchedDefault[NS_FORMULA] = true;
$wgNamespacesToBeSearchedDefault[NS_PERSON] = true;
$wgNamespacesToBeSearchedDefault[NS_PUBLICATION] = true;
$wgNamespacesToBeSearchedDefault[NS_SOFTWARE] = true;
$wgNamespacesToBeSearchedDefault[NS_DATASET] = true;



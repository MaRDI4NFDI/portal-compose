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
define("NS_THEOREM", 4220);
define("NS_THEOREM_TALK", 4221);
define("NS_RESEARCH_FIELD", 4222);
define("NS_RESEARCH_FIELD_TALK", 4223);
define("NS_RESEARCH_PROBLEM", 4224);
define("NS_RESEARCH_PROBLEM_TALK", 4225);
define("NS_MODEL", 4226);
define("NS_MODEL_TALK", 4227);
define("NS_QUANTITY", 4228);
define("NS_QUANTITY_TALK", 4229);
define("NS_TASK", 4230);
define("NS_TASK_TALK", 4231);

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
$wgExtraNamespaces[NS_THEOREM] = "Theorem";
$wgExtraNamespaces[NS_THEOREM_TALK] = "Theorem_talk";
$wgExtraNamespaces[NS_RESEARCH_FIELD] = "Research_field";
$wgExtraNamespaces[NS_RESEARCH_FIELD_TALK] = "Research_field_talk";
$wgExtraNamespaces[NS_RESEARCH_PROBLEM] = "Research_problem";
$wgExtraNamespaces[NS_RESEARCH_PROBLEM_TALK] = "Research_problem_talk";
$wgExtraNamespaces[NS_MODEL] = "Model";
$wgExtraNamespaces[NS_MODEL_TALK] = "Model_talk";
$wgExtraNamespaces[NS_QUANTITY] = "Quantity";
$wgExtraNamespaces[NS_QUANTITY_TALK] = "Quantity_talk";
$wgExtraNamespaces[NS_TASK] = "Task";
$wgExtraNamespaces[NS_TASK_TALK] = "Task_talk";

$wgNamespaceProtection[NS_FORMULA] = array( 'overwriteprofilepages' ); 
$wgNamespaceProtection[NS_PERSON] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_PUBLICATION] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_SOFTWARE] = array( 'overwriteprofilepages' ); 
$wgNamespaceProtection[NS_DATASET] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_COMMUNITY] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_WORKFLOW] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_ALGORITHM] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_SERVICE] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_THEOREM] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_RESEARCH_FIELD] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_RESEARCH_PROBLEM] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_MODEL] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_QUANTITY] = array( 'overwriteprofilepages' );
$wgNamespaceProtection[NS_TASK] = array( 'overwriteprofilepages' );


$wgGroupPermissions['user']['overwriteprofilepages'] = true;

$wgContentNamespaces[] = NS_FORMULA;
$wgContentNamespaces[] = NS_PERSON;
$wgContentNamespaces[] = NS_PUBLICATION;
$wgContentNamespaces[] = NS_SOFTWARE;
$wgContentNamespaces[] = NS_DATASET;
$wgContentNamespaces[] = NS_COMMUNITY;
$wgContentNamespaces[] = NS_WORKFLOW;
$wgContentNamespaces[] = NS_ALGORITHM;
$wgContentNamespaces[] = NS_SERVICE;
$wgContentNamespaces[] = NS_THEOREM;
$wgContentNamespaces[] = NS_RESEARCH_FIELD;
$wgContentNamespaces[] = NS_RESEARCH_PROBLEM;
$wgContentNamespaces[] = NS_MODEL;
$wgContentNamespaces[] = NS_QUANTITY;
$wgContentNamespaces[] = NS_TASK;



$wgNamespacesToBeSearchedDefault[NS_FORMULA] = true;
$wgNamespacesToBeSearchedDefault[NS_PERSON] = true;
$wgNamespacesToBeSearchedDefault[NS_PUBLICATION] = true;
$wgNamespacesToBeSearchedDefault[NS_SOFTWARE] = true;
$wgNamespacesToBeSearchedDefault[NS_DATASET] = true;



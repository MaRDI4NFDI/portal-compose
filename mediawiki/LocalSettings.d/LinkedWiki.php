<?php
# wfLoadExtension( 'LinkedWiki' );

# Linked-Wiki Configuration
$wgLinkedWikiConfigSPARQLServices["http://wikibase.svc"] = array(
    "debug" => false,
    "isReadOnly" => true,
    "endpointRead" => "http://wdqs.svc:9999/bigdata/namespace/wdq/sparql",
    "typeRDFDatabase" => "blazegraph",
    "HTTPMethodForRead" => "GET",
    "lang" => "en"
);

$wgLinkedWikiSPARQLServiceByDefault= "http://wikibase.svc";

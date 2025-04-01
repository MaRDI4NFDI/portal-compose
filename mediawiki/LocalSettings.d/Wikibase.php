<?php
## Wikibase

wfLoadExtension( 'WikibaseClient', "$IP/extensions/Wikibase/extension-client.json" );
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

$wikibaseHost = getenv( 'WIKIBASE_SCHEME' ) . '://' . getenv( 'WIKIBASE_HOST' );
$portalHost = $wikibaseHost;
if ( getenv( 'WIKIBASE_HOST' ) === 'localhost' ) {
		$wikibaseHost = getenv( 'WIKIBASE_SCHEME' ) . '://wikibase.svc';
		$portalHost = getenv( 'WIKIBASE_SCHEME' ) . '://localhost:' . getenv( 'WIKIBASE_PORT' );
}

# enable linking between wikibase and content pages
$wgWBRepoSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteLinkGroups'] = [ 'mathematics' ];
$wgWBClientSettings['siteGlobalID'] = 'mardi';
$wgWBClientSettings['repoUrl'] = $portalHost;
$wgWBClientSettings['repoScriptPath'] = '/w';
$wgWBClientSettings['repoArticlePath'] = '/wiki/$1';
$wgWBClientSettings['entitySources'] = [
		'mardi_source' => [
				'repoDatabase' => 'my_wiki',
				'baseUri' => $wikibaseHost . '/entity/',
				'entityNamespaces' => [
						'item' => 120,
						'property' => 122,
				],
				'rdfNodeNamespacePrefix' => 'wd',
				'rdfPredicateNamespacePrefix' => '',
				'interwikiPrefix' => '',
		],
];
$wgWBClientSettings['itemAndPropertySourceName'] = 'mardi_source';
// my_wiki is the MaRDI database
$wgLocalDatabases = [ 'wiki_swmath', 'my_wiki' ];

// https://github.com/MaRDI4NFDI/portal-compose/issues/224
$wgNamespacesToBeSearchedDefault[122] = true; // WB_PROPERTY_NAMESPACE===122

if ( $wgDBname === 'my_wiki' ) {

	wfLoadExtension( 'WikibaseRepository', "$IP/extensions/Wikibase/extension-repo.json" );
	// from https://github.com/wikimedia/mediawiki-extensions-Wikibase/blob/f2bd35609b6bf3f8d38ef8c78d2f340497906706/repo/includes/RepoHooks.php#L170C1-L180C61
	$wgExtraNamespaces[120] = 'Item';
	$wgExtraNamespaces[121] = 'Item_talk';
	$wgExtraNamespaces[122] = 'Property';
	$wgExtraNamespaces[123] = 'Property_talk';
	// do not declare namespaces if that would be done by default https://gerrit.wikimedia.org/r/c/mediawiki/extensions/Wikibase/+/933906 https://phabricator.wikimedia.org/T291617
	$wgWBRepoSettings['defaultEntityNamespaces'] = false;
	$wgWBRepoSettings['entitySources'] = [
			'mardi_source' => [
				'repoDatabase' => 'my_wiki',
				'baseUri' => $wikibaseHost . '/entity/',
				'entityNamespaces' => [
						'item' => 120,
						'property' => 122,
				],
				'rdfNodeNamespacePrefix' => 'wd',
				'rdfPredicateNamespacePrefix' => '',
				'interwikiPrefix' => '',
			],
	];
	$wgWBRepoSettings['localEntitySourceName'] = 'mardi_source';
	$wgWBRepoSettings['localClientDatabases'] = [
		'mardi' => 'my_wiki',
		'swmath' => 'wiki_swmath'
	];
	// insert site with
	// php addSite.php --filepath=https://portal.mardi4nfdi.de/w/\$1 --pagepath=https://portal.mardi4nfdi.de/wiki/\$1 --language en --interwiki-id mardi mardi mathematics
	// php addSite.php --filepath=https://staging.swmath.org/w/\$1 --pagepath=https://staging.swmath.org/wiki/\$1 --language en --interwiki-id swmath swmath mathematics

	# Pingback
	$wgWBRepoSettings['wikibasePingback'] = false;

	# Increase string size limits
	$wgWBRepoSettings['string-limits'] = [
		'VT:string' => [
			'length' => 200000,
		],
		'multilang' => [
			'length' => 2000,
		],
		'VT:monolingualtext' => [
			'length' => 1000,
		],
	];
	# Math-specific string limits
	$wgMathSearchContentTexMaxLength = 30000;
	$wgMathTexMaxLength = 30000;
	// https://github.com/ProfessionalWiki/WikibaseLocalMedia
	## WikibaseLocalMedia Configuration
	## NOTE: WikibaseLocalMedia does currently not work in a client only setup.
	wfLoadExtension( 'WikibaseLocalMedia' );
	// https://www.mediawiki.org/wiki/Extension:EntitySchema
	## EntitySchema Configuration
	wfLoadExtension( 'EntitySchema' );
	// https://www.mediawiki.org/wiki/Extension:WikibaseCirrusSearch
	wfLoadExtension( 'WikibaseCirrusSearch' );

	if ( getenv( 'MW_ELASTIC_HOST' ) !== false ) {
		$wgCirrusSearchServers = [ $_ENV['MW_ELASTIC_HOST'] ];
		$wgSearchType = 'CirrusSearch';
		$wgCirrusSearchExtraIndexSettings['index.mapping.total_fields.limit'] = 5000;
		$wgWBCSUseCirrus = true;
		$wgWBRepoSettings['searchIndexProperties'] = [
			"P2", // Digital Library of Mathematical Functions ID
			"P5", // xml-id
			"P6", // equation number
			"P11", // Wikidata PID
			"P12", // Wikidata QID
			"P13", // swMATH work ID
			"P20", // ORCID iD
			"P21", // arXiv ID
			"P23", // ISBN-13
			"P24", // ISSN
			"P27", // DOI
			"P41", // FactGrid property ID
			"P75", // ISBN-10
			"P76", // ISBN publisher prefix
			"P77", // ISBN identifier group
			"P78", // Depósito Legal ID
			"P79", // Open Library ID
			"P80", // OCLC work ID
			"P81", // OCLC control number
			"P82", // Bibliothèque nationale de France ID
			"P83", // GND ID
			"P84", // Biblioteca Nacional de España ID
			"P85", // Library of Congress authority ID
			"P86", // Nationale Thesaurus voor Auteurs ID
			"P87", // Portuguese National Library author ID
			"P88", // NDL Authority ID
			"P89", // VIAF ID
			"P90", // National Library of Israel ID (old)
			"P92", // Mix'n'match catalog ID
			"P97", // identifiers.org prefix
			"P100", // Global Trade Item Number
			"P110", // CPPAP ID
			"P111", // ISSN-L
			"P134", // Handle ID
			"P135", // DOI prefix
			"P136", // EIDR content ID
			"P137", // ACM Digital Library citation ID
			"P138", // IEEE Xplore document ID
			"P152", // Ringgold ID
			"P153", // Authorea author ID
			"P154", // NARCIS researcher ID
			"P155", // ISNI
			"P156", // ACM Digital Library author ID
			"P157", // IEEE Xplore author ID
			"P158", // MR Author ID
			"P172", // arXiv author ID
			"P173", // ChemRxiv ID
			"P174", // BioRxiv ID
			"P175", // Rxivist preprint ID
			"P176", // ORKG ID
			"P224", // Mathematical Reviews ID
			"P225", // zbMATH Open document ID
			"P226", // Mathematics Subject Classification ID
			"P227", // Zenodo ID
			"P229", // CRAN project
			"P235", // BNCF Thesaurus ID
			"P239", // Freebase ID
			"P241", // PSH ID
			"P242", // Art & Architecture Thesaurus ID
			"P243", // TED topic ID
			"P244", // Gran Enciclopèdia Catalana ID (former scheme)
			"P245", // ISOCAT id
			"P247", // Guardian topic ID
			"P248", // Quora topic ID
			"P249", // BBC Things ID
			"P252", // JSTOR topic ID
			"P255", // Le Monde diplomatique subject ID
			"P257", // BAnQ work ID
			"P258", // BN (Argentine) editions
			"P259", // Library of the National Congress of Argentina ID
			"P260", // Goodreads version/edition ID
			"P261", // Babelio work ID
			"P266", // Dagens Næringsliv topic ID
			"P267", // U.S. National Archives Identifier
			"P268", // Iconclass notation
			"P269", // Encyclopædia Universalis ID
			"P270", // BabelNet ID
			"P271", // Europeana Fashion Vocabulary ID
			"P273", // subreddit
			"P275", // Brockhaus Enzyklopädie online ID
			"P276", // Encyclopædia Britannica Online ID
			"P277", // TDKIV term ID
			"P278", // MeSH descriptor ID
			"P279", // Klexikon article ID
			"P280", // Treccani's Dizionario di Storia ID
			"P281", // Australian Educational Vocabulary ID
			"P283", // Wolfram Language entity type
			"P284", // OmegaWiki Defined Meaning
			"P285", // HDS ID
			"P288", // Nomenclature for Museum Cataloging
			"P289", // French Vikidia ID
			"P290", // English Vikidia ID
			"P291", // Spanish Vikidia ID
			"P292", // UNESCO Thesaurus ID
			"P293", // De Agostini ID
			"P294", // KBpedia ID
			"P295", // Grove Art Online ID
			"P296", // RKD thesaurus ID
			"P297", // BBC News topic ID
			"P298", // Twitter topic ID
			"P299", // Joconde object type ID
			"P300", // GitHub topic
			"P301", // PACTOLS thesaurus ID
			"P302", // Ávvir topic ID
			"P303", // Zhihu topic ID
			"P304", // WordNet 3.1 Synset ID
			"P305", // Jewish Encyclopedia ID (Russian)
			"P306", // Dewey Decimal Classification
			"P307", // STW Thesaurus for Economics ID
			"P309", // National Library of Israel J9U ID
			"P310", // ABC News topic ID
			"P311", // Portable Antiquities Scheme object type identifier
			"P312", // YSO ID
			"P313", // FISH Archaeological Objects Thesaurus ID
			"P314", // Giant Bomb ID
			"P315", // NKCR AUT ID
			"P316", // FactGrid item ID
			"P317", // UK Archival Thesaurus ID
			"P318", // Golden ID
			"P320", // Amazon.com browse node
			"P323", // GS1 GPC code
			"P324", // National Library of Latvia ID
			"P325", // Store norske leksikon ID
			"P327", // Pixiv Encyclopedia ID
			"P328", // Google Product Taxonomy ID
			"P330", // National Historical Museums of Sweden ID
			"P332", // Universal Decimal Classification
			"P333", // NALT ID
			"P336", // PRONOM software ID
			"P337", // Cultureel Woordenboek ID
			"P350", // Google Play Store app ID
			"P351", // Debian stable package
			"P352", // F-Droid package
			"P353", // App Store app ID
			"P354", // Free Software Directory entry
			"P358", // Gentoo package
			"P359", // Arch Linux package
			"P360", // Ubuntu package
			"P361", // Fedora package
			"P366", // Yahoo Answers category
			"P367", // IPTC NewsCode
			"P368", // Disney A to Z ID
			"P371", // IAB code
			"P372", // ASC Leiden Thesaurus ID
			"P373", // Microsoft Academic ID
			"P374", // EuroVoc ID
			"P375", // MeSH tree code
			"P376", // UK Parliament thesaurus ID
			"P377", // Open Library subject ID
			"P378", // IEV number
			"P379", // MetaSat ID
			"P380", // Namuwiki ID
			"P381", // DeCS ID
			"P382", // Treccani's Enciclopedia della Matematica ID
			"P383", // Treccani's Enciclopedia Italiana ID
			"P385", // Dizionario delle Scienze Fisiche ID
			"P386", // Treccani ID
			"P387", // All-Science Journal Classification Codes
			"P388", // OpenAlex ID
			"P390", // WIPO Pearl term ID
			"P391", // Fandom article ID
			"P393", // NE.se ID
			"P394", // Techopedia ID
			"P395", // word in DPEJ of RAE ID
			"P396", // IHO Hydrographic Dictionary (S-32) Number
			"P397", // Reddit topic ID
			"P398", // Dictionary of Archives Terminology ID
			"P399", // Analysis & Policy Observatory term ID
			"P409", // DistroWatch ID
			"P412", // Framalibre ID
			"P414", // Open Hub ID
			"P415", // interwiki prefix at Wikimedia
			"P416", // AlternativeTo software ID
			"P419", // Share-VDE 1.0 author ID
			"P420", // IdRef ID
			"P421", // Libraries Australia ID
			"P422", // CANTIC ID (former scheme)
			"P424", // Google Maps Customer ID
			"P425", // CAGE code
			"P426", // EU Transparency Register ID
			"P435", // NUKAT ID
			"P436", // NORAF ID
			"P438", // Facebook ID
			"P439", // Twitter username
			"P440", // Twitter user numeric ID
			"P441", // Google+ ID
			"P442", // YouTube channel ID
			"P446", // Instagram username
			"P447", // Great Russian Encyclopedia Online ID
			"P448", // GRID ID
			"P451", // PM20 folder ID
			"P454", // RoMEO publisher ID
			"P455", // ROR ID
			"P456", // Flickr user ID
			"P457", // Publons publisher ID
			"P458", // WorldCat Identities ID
			"P460", // CiNii Books author ID
			"P461", // CiNii Research ID
			"P462", // FAST ID
			"P464", // SHARE Catalogue author ID
			"P465", // CANTIC ID
			"P466", // Crossref funder ID
			"P467", // Bing entity ID
			"P468", // LinkedIn organization ID
			"P469", // ELNET ID
			"P470", // National Library of Lithuania ID
			"P471", // National Library of Korea ID
			"P472", // BAnQ author ID
			"P473", // Canadiana Name Authority ID
			"P474", // RERO ID (obsolete)
			"P475", // SNAC ARK ID
			"P476", // NLA Trove people ID
			"P477", // Alexander Turnbull Library ID
			"P478", // App Store developer ID
			"P479", // Google Play developer slug
			"P480", // Bloomberg company ID
			"P481", // PermID
			"P482", // OpenCorporates ID
			"P484", // IRS Employer Identification Number
			"P485", // Crunchbase organization ID
			"P486", // Online Books Page author ID
			"P487", // Goodreads author ID
			"P488", // LibraryThing author ID
			"P489", // Glassdoor company ID
			"P490", // DPLA subject term
			"P491", // ResearchGate institute ID
			"P492", // C-SPAN organization ID
			"P498", // Mastodon address
			"P499", // Provenio UUID
			"P501", // Den Store Danske ID
			"P502", // Encyclopedia of China (Third Edition) ID
			"P503", // ODLIS ID
			"P507", // File Format Wiki page ID
			"P512", // Pro-Linux.de DBApp ID
			"P513", // Repology project name
			"P514", // WikiSkripta ID
			"P515", // AUR package
			"P516", // FreeBSD port
			"P517", // NetBSD package
			"P518", // OpenBSD port
			"P519", // Guix Variable Name
			"P520", // Homebrew formula name
			"P521", // Docker Hub repository
			"P523", // Debian Package Tracker ID
			"P524", // openSUSE package
			"P525", // Cyprus Bibliography ID
			"P526", // Wine AppDB ID
			"P527", // OSDN project
			"P528", // SILL software ID
			"P529", // Programming Language Database ID
			"P532", // SPDX license ID
			"P534", // UNSPSC Code
			"P535", // Library of Congress Genre/Form Terms ID
			"P536", // Hrvatska enciklopedija ID
			"P537", // TOPCMB ID
			"P539", // Gyldendals Teaterleksikon ID
			"P541", // Krugosvet article
			"P543", // Thesaurus for Graphic Materials ID
			"P545", // Google Knowledge Graph ID
			"P546", // Catalan Vikidia ID
			"P547", // PersonalData.IO ID
			"P548", // EU Knowledge Graph item ID
			"P549", // Norwegian thesaurus on genre and form identifier
			"P552", // YSA ID
			"P553", // Joconde domain ID
			"P574", // Online PWN Encyclopedia ID
			"P575", // Wikibase Registry ID
			"P582", // Web of Science ID (work)
			"P583", // GSSO ID
			"P589", // IMDb ID
			"P590", // Internet Content Provider Registration Record ID
			"P633", // Encyclopedia of Modern Ukraine ID
			"P636", // Catholic Encyclopedia ID
			"P637", // Panorama de l'art ID
			"P639", // NicoNicoPedia ID
			"P640", // Wolfram Language unit code
			"P641", // Quora topic ID (Spanish)
			"P642", // TDV İslam Ansiklopedisi ID
			"P644", // TV Tropes identifier
			"P647", // ICD-11 (foundation)
			"P648", // UMLS CUI
			"P650", // SNOMED CT identifier
			"P651", // ID (MMS)
			"P652", // AniDB tag ID
			"P653", // All the Tropes identifier
			"P655", // UN/CEFACT Common Code
			"P657", // Fossilworks taxon ID
			"P658", // Archive of Our Own tag
			"P659", // Personality Database profile ID
			"P660", // Google News topics ID
			"P661", // Scopus author ID
			"P663", // GitHub username
			"P664", // LinkedIn personal profile ID
			"P666", // Google Scholar author ID
			"P667", // RePEc Short-ID
			"P668", // Mathematics Genealogy Project ID
			"P669", // Dimensions author ID
			"P670", // Prabook ID
			"P671", // ResearcherID
			"P672", // Loop ID
			"P673", // DBLP author ID
			"P674", // Publons author ID
			"P676", // zbMATH author ID
			"P679", // Springer Nature person ID
			"P680", // SNSF person ID
			"P682", // ResearchGate profile ID
			"P683", // CIÊNCIAVITAE ID
			"P684", // INSPIRE-HEP author ID
			"P685", // Renacyt ID
			"P686", // ProQuest document ID
			"P687", // Academic Tree ID
			"P688", // SELIBR ID
			"P689", // Libris-URI
			"P690", // Mendeley person ID
			"P691", // data.gouv.fr dataset ID
			"P693", // SISSA Digital Library author ID
			"P694", // Semantic Scholar author ID
			"P696", // Catalogus Professorum Academiae Groninganae ID
			"P698", // ARPI author ID
			"P699", // IRIS UNINA author ID
			"P700", // IRIS UNICZ author ID
			"P701", // PLWABN ID
			"P702", // HAL author ID
			"P703", // Persée author ID
			"P705", // KANTO ID
			"P707", // BHL creator ID
			"P708", // National Academy of Sciences member ID
			"P709", // Guggenheim fellows ID
			"P710", // Polish scientist ID
			"P711", // PAN member
			"P713", // BNB person ID
			"P714", // Archive ouverte UNIGE ID
			"P715", // KBR person ID
			"P716", // ArTS author ID
			"P717", // IRIS UNIROMA2 author ID
			"P718", // Who's Who UK ID
			"P719", // GEPRIS person ID
			"P720", // Gateway to Research person ID
			"P723", // Archaeology Data Service person ID
			"P724", // CVR person ID
			"P725", // URN-NBN
			"P726", // AcademiaNet ID
			"P727", // ZooBank author ID
			"P729", // Basisklassifikation
			"P730", // Latvian National Encyclopedia Online ID
			"P731", // AGROVOC ID
			"P732", // OpenCitations bibliographic resource ID
			"P733", // NLM Unique ID
			"P734", // JUFO ID
			"P735", // SAGE journal ID
			"P737", // Directory of Open Access Journals ID
			"P738", // Scopus source ID
			"P739", // Crossref journal ID
			"P740", // Mir@bel journal ID
			"P741", // Scilit journal ID
			"P743", // HAL journal ID
			"P744", // e-Rad researcher number
			"P746", // EThOS thesis ID
			"P747", // Encyclopedia of Australian Science ID
			"P748", // IRIS UNIBO author ID
			"P749", // Theses.fr person ID
			"P750", // National Thesis Number (France)
			"P751", // NOR
			"P752", // CONOR.SI ID
			"P753", // The Conversation author ID
			"P754", // Cyprus University of Technology ID
			"P755", // Muck Rack journalist ID
			"P756", // ARCA author ID
			"P759", // Crunchbase person ID
			"P760", // UNICA IRIS author ID
			"P761", // Flanders Arts Institute person ID
			"P762", // Bionomia ID
			"P764", // NSK ID
			"P767", // Stack Exchange user ID
			"P768", // Gateway to Research Project ID
			"P770", // IxTheo authority ID
			"P771", // Isidore scholar ID
			"P772", // Open Science Framework ID
			"P773", // IUF member ID
			"P774", // SBN author ID
			"P775", // IRIS UNIVPM author ID
			"P776", // ResearchGate contributions ID
			"P777", // Lattes Platform number
			"P778", // Repositório da Produção da USP person ID
			"P782", // University of Amsterdam Album Academicum ID
			"P783", // openMLOL author ID
			"P784", // Hamburger Professorinnen- und Professorenkatalog ID
			"P785", // AperTO author ID
			"P786", // Treccani's Dizionario di Filosofia ID
			"P787", // abART person ID
			"P788", // Biographical Dictionary of the Czech Lands ID
			"P789", // vedidk ID
			"P790", // University of Barcelona authority ID (former scheme)
			"P791", // University of Barcelona authority ID
			"P792", // Tabakalera ID
			"P793", // CRIStin ID
			"P794", // Dictionary Grierson ID
			"P795", // Fellow of the Royal Society ID
			"P796", // French Academy of Sciences member ID
			"P798", // Estonian Research Portal person ID
			"P799", // CERL Thesaurus ID
			"P800", // Harvard Index of Botanists ID
			"P801", // UGentMemorialis professor ID
			"P802", // Inguma author ID
			"P803", // DBC author ID
			"P804", // autores.uy ID
			"P805", // Radio Radicale person ID
			"P806", // Wikimedia username
			"P807", // National Library of Iceland ID
			"P808", // Companies House officer ID
			"P810", // Leidse Hoogleraren ID
			"P811", // ZOBODAT person ID
			"P812", // SSRN author ID
			"P813", // Podchaser creator ID
			"P816", // IRIS Sapienza author ID
			"P817", // FLORE author ID
			"P818", // IRIS UNIPG author ID
			"P819", // IRIS UNIPA author ID
			"P820", // Dialnet author ID
			"P821", // Figshare author ID
			"P822", // Baidu ScholarID
			"P823", // IRIS FBK author ID
			"P824", // Padua Research Archive author ID
			"P825", // IRIS UNISA author ID
			"P826", // IRIS UNIVAQ author ID
			"P827", // INAPP author ID
			"P828", // botanist author abbreviation
			"P829", // IPNI author ID
			"P831", // Re.Public@Polimi author ID
			"P832", // Encyclopedia of Life ID
			"P836", // NCBI taxonomy ID
			"P837", // ITIS TSN
			"P841", // WoRMS-ID for taxa
			"P842", // Fauna Europaea ID
			"P843", // Dyntaxa ID
			"P844", // GBIF taxon ID
			"P845", // ZooBank ID for name or act
			"P847", // New Zealand Organisms Register ID
			"P848", // EPPO Code
			"P849", // BugGuide taxon ID
			"P850", // Nederlands Soortenregister ID
			"P851", // ADW taxon ID
			"P852", // Fauna Europaea New ID
			"P853", // Open Food Facts food category ID
			"P854", // IRMNG ID
			"P855", // iNaturalist taxon ID
			"P856", // Canadian Encyclopedia article ID
			"P857", // Plant Parasites of Europe ID
			"P858", // Belgian Species List ID
			"P859", // DR topic ID
			"P861", // Great Aragonese Encyclopedia ID
			"P862", // Visual Novel Database ID
			"P864", // Fossiilid.info ID
			"P865", // Encyclopedia of the Great Plains ID
			"P866", // CALS Encyclopedia of Arkansas ID
			"P867", // e-WV: The West Virginia Encyclopedia ID
			"P868", // Iranica ID
			"P870", // Colon Classification
			"P871", // CAB ID
			"P873", // BOLD Systems taxon ID
			"P874", // Encyclopedia of Melbourne ID
			"P875", // NBIC taxon ID
			"P876", // Plazi ID
			"P877", // Australian Faunal Directory ID
			"P878", // Catalogue of Life ID
			"P882", // Endemia.nc animal taxon ID
			"P883", // Internet Encyclopedia of Philosophy ID
			"P885", // New York Times topic ID
			"P886", // uBio ID
			"P887", // Larousse ID
			"P888", // Maine: An Encyclopedia ID
			"P889", // Arabic Ontology ID
			"P890", // New Georgia Encyclopedia ID
			"P891", // Handbook of Texas ID
			"P892", // The Encyclopedia of Oklahoma History and Culture ID
			"P893", // LEM ID
			"P894", // Library of Congress Children's Subject Headings ID
			"P895", // Open Tree of Life ID
			"P897", // The Encyclopedia of Fantasy ID
			"P898", // AEDA subject keyword ID
			"P899", // Hesperomys taxon ID
			"P900", // Fandom wiki ID
			"P901", // GEMET ID
			"P903", // MSW ID
			"P906", // NBN System Key
			"P907", // Elhuyar ZTH ID
			"P913", // USGS Thesaurus ID
			"P914", // UNBIS Thesaurus ID
			"P915", // archINFORM keyword ID
			"P916", // AE member ID
			"P917", // CNRS Talent page
			"P919", // Babelio author ID
			"P920", // Unz Review author ID
			"P926", // PRONOM file format ID
			"P927", // Library of Congress Format Description Document ID
			"P931", // Kaitai Struct format gallery ID
			"P933", // Australian Thesaurus of Education Descriptors ID
			"P934", // Zotero ID
			"P935", // American Academy in Rome ID
			"P936", // Hacker News username
			"P937", // Semion author ID
			"P938", // IRIS UNITN author ID
			"P939", // BOE ID
			"P940", // DOGC ID
			"P941", // KU Leuven person ID
			"P943", // 500 Queer Scientists profile
			"P944", // Catalogus Professorum Academiae Rheno-Traiectinae ID
			"P945", // IRIS-OpenPub author ID
			"P946", // DNB edition ID
			"P947", // researchportal.helsinki.fi profile ID
			"P948", // IMIS person ID
			"P949", // GTAA ID
			"P950", // NLP ID (old)
			"P951", // IRIS UNIFE author ID
			"P952", // SICRIS researcher ID
			"P953", // IRIS UNIPV author ID
			"P954", // IRIS UNIROMA3 author ID
			"P955", // MTMT author ID
			"P959", // France Culture person ID
			"P960", // TED speaker ID
			"P961", // AstroGen ID
			"P962", // Canadiana Authorities ID (former scheme)
			"P963", // Who's Who in France biography ID
			"P964", // Canal-U person ID
			"P965", // CONOR.SR ID
			"P966", // SAIA authority ID
			"P967", // EBAF authority ID
			"P968", // PubMed ID
			"P969", // Rxivist author ID
			"P970", // Terezín Memorial Database ID
			"P971", // Brapci author ID
			"P972", // PubliCatt author ID
			"P973", // Aisberg author ID
			"P974", // SUDOC editions
			"P975", // Radio France person ID
			"P976", // IRIS UNIUD author ID
			"P977", // Biblioteca Digital Curt Nimuendajú ID
			"P980", // YouTube video ID
			"P982", // IRIS UNICAMPANIA author ID
			"P990", // IRIS UNIUPO author ID
			"P991", // Bicocca Open Archive author ID
			"P993", // IRIS Verona author ID
			"P994", // IRIS UNICT author ID
			"P995", // IRIS UNIMOL author ID
			"P996", // Keybase username
			"P997", // ARUd'A author ID
			"P998", // Leopoldina member ID (superseded)
			"P999", // Deutsche Biographie (GND) ID
			"P1001", // NIPS Proceedings author ID
			"P1003", // Amazon author ID
			"P1004", // National Library of Brazil ID
			"P1005", // Cairn author ID
			"P1006", // IAU member ID
			"P1007", // Prophy author ID
			"P1008", // ScienceOpen author ID
			"P1009", // Medium username
			"P1010", // Uppsala University Alvin ID
			"P1011", // HKCAN ID
			"P1012", // Enciclopedia dei ragazzi ID
			"P1013", // Spanish Cultural Heritage thesauri ID
			"P1017", // World Heritage Site ID
			"P1020", // MusicBrainz area ID
			"P1021", // Facebook Places ID
			"P1026", // UN/LOCODE
			"P1028", // Who's on First ID
			"P1030", // Interlingual Index ID
			"P1031", // GeoNames ID
			"P1032", // GNS Unique Feature ID
			"P1043", // ISO 3166-1 alpha-2 code
			"P1044", // ISO 3166-1 alpha-3 code
			"P1045", // ISO 3166-1 numeric code
			"P1055", // Curlie ID
			"P1056", // IOC country code
			"P1057", // FIPS 10-4 (countries and regions)
			"P1059", // British Museum person or institution ID
			"P1073", // INSEE countries and foreign territories code
			"P1075", // Getty Thesaurus of Geographic Names ID
			"P1077", // Kunstindeks Danmark Artist ID
			"P1080", // OpenStreetMap relation ID
			"P1082", // GACS ID
			"P1091", // CIVICUS Monitor country entry
			"P1093", // ITU/ISO/IEC object ID
			"P1094", // Marine Regions Geographic ID
			"P1095", // archINFORM location ID
			"P1098", // Statoids ID
			"P1099", // ITU letter code
			"P1100", // WIPO ST.3
			"P1101", // Invasive Species Compendium Datasheet ID
			"P1102", // LoC and MARC vocabularies ID
			"P1103", // Comic Vine ID
			"P1104", // Orthodox Encyclopedia ID
			"P1109", // GeoNLP ID
			"P1112", // Criminological Thesaurus ID
			"P1113", // Thesaurus Sozialwissenschaften ID
			"P1116", // TasteAtlas ID
			"P1117", // ILO Thesaurus ID
			"P1120", // iNaturalist place ID
			"P1121", // SVKKL authority ID
			"P1122", // iDAI.gazetteer ID
			"P1123", // Yle topic ID
			"P1125", // Armeniapedia ID
			"P1126", // The World Factbook country ID
			"P1128", // Postimees topic ID
			"P1129", // M.49 code
			"P1133", // PM20 geo code
			"P1134", // OBO Gazetteer ID
			"P1137", // OpenStreetMap node ID
			"P1138", // Le Figaro tag ID
			"P1139", // Mérimée ID
			"P1144", // IRIS UNISS author ID
			"P1145", // BDELIS ID
			"P1146", // Arnet Miner author ID
			"P1147", // Justia Patents inventor ID
			"P1149", // National Library of Greece ID
			"P1150", // History of Modern Biomedicine ID
			"P1151", // elibrary.ru person ID
			"P1152", // Researchmap ID
			"P1153", // J-GLOBAL ID
			"P1154", // IRIS UNIPARTHENOPE author ID
			"P1155", // Oberwolfach mathematician ID
			"P1156", // IRIS UNIBA author ID
			"P1157", // IRIS UNIMI author ID
			"P1158", // FAPESP researcher ID
			"P1160", // Pontificia Università della Santa Croce ID
			"P1161", // CineMagia person ID
			"P1162", // OpenEdition Books author ID
			"P1163", // USiena air author ID
			"P1164", // BHCL UUID
			"P1165", // SNK ID
			"P1166", // MacArthur Fellows Program ID
			"P1167", // Podchaser numeric creator ID
			"P1168", // Munzinger person ID
			"P1169", // Perlentaucher ID
			"P1170", // Leopoldina member ID (new)
			"P1171", // IRIS UNIMORE author ID
			"P1172", // Australian honours ID
			"P1173", // ICCF player ID
			"P1174", // USCF player ID
			"P1175", // Ballotpedia ID
			"P1176", // amateur radio callsign
			"P1178", // RPGGeek ID
			"P1180", // Semantic Scholar paper ID
			"P1181", // Fatcat ID
			"P1183", // LittleSis people ID
			"P1184", // Te Papa agent ID
			"P1192", // MathWorld ID
			"P1193", // Dictionary of Algorithms and Data Structures ID
			"P1194", // Brilliant Wiki ID
			"P1195", // nLab ID
			"P1197", // Scholarpedia article ID
			"P1198", // Treccani's Dizionario di Economia e Finanza ID
			"P1199", // Enciclopedia della Scienza e della Tecnica ID
			"P1200", // Semantic Scholar topic ID
			"P1201", // Academia.edu topic ID
			"P1202", // Encyclopedia of Mathematics wiki ID
			"P1203", // MeSH concept ID
			"P1204", // MeSH term ID
			"P1205", // ERIC Thesaurus ID
			"P1206", // Library of Congress Classification
			"P1207", // Encyclopaedia Herder concept ID
			"P1208", // Great Russian Encyclopedia portal ID
			"P1210", // Wolfram Language quantity ID
			"P1211", // Italian Vikidia ID
			"P1213", // IUPAC Gold Book ID
			"P1215", // Encyclopedia of China ID (Second Edition)
			"P1216", // EMBO member ID
			"P1217", // All-Russian Mathematical Portal ID
			"P1218", // IRIS SNS author ID
			"P1219", // Legacy.com person ID
			"P1221", // BES-Net user ID
			"P1223", // IRIS UNIGE author ID
			"P1224", // HAS member ID
			"P1225", // Doktori.hu ID
			"P1226", // EU Research participant ID
			"P1227", // EU VAT number
			"P1228", // PORTO@Iris author ID
			"P1230", // Akadem person ID
			"P1233", // TaDiRAH ID
			"P1234", // ANZSRC 2020 FoR ID
			"P1239", // IGI Global Dictionary ID
			"P1240", // IRIS GSSI author ID
			"P1256", // Relations Ontology ID
			"P1280", // TripAdvisor ID
			"P1282", // UIC alphabetical country code
			"P1289", // Wolfram Language entity code
			"P1290", // Wall Street Journal topic ID
			"P1292", // Dagens Nyheter topic ID
			"P1301", // Project Gutenberg author ID
			"P1305", // UIC numerical country code
			"P1307", // Global Anabaptist Mennonite Encyclopedia Online ID
			"P1310", // The Top Tens ID
			"P1311", // World Encyclopedia of Puppetry Arts ID
			"P1312", // Aozora Bunko author ID
			"P1315", // Know Your Meme ID
			"P1316", // Basque Vikidia ID
			"P1317", // JORFSearch organization ID
			"P1318", // Österreichisches Musiklexikon Online ID
			"P1319", // Gynopedia ID
			"P1320", // edition humboldt digital ID
			"P1321", // SAPA ID
			"P1322", // AIATSIS Place Thesaurus ID
			"P1323", // CNA topic ID
			"P1325", // Joconde location ID
			"P1326", // WOEID
			"P1327", // HASC
			"P1328", // Naver Encyclopedia ID
			"P1329", // Daum Encyclopedia ID
			"P1330", // Encyclopedia of the History of Ukraine ID
			"P1331", // bashenc.online ID
			"P1332", // Schoenberg Database of Manuscripts place ID
			"P1333", // Al-Jazeera topic ID
			"P1334", // TermCymru ID
			"P1337", // LocalWiki ID
			"P1338", // Der Spiegel topic ID
			"P1339", // Google Arts & Culture entity ID
			"P1340", // museum-digital place ID
			"P1341", // Wikisimpsons ID
			"P1342", // Media Arts Database ID
			"P1343", // Urban Electric Transit country ID
			"P1349", // Data Commons ID
			"P1350", // Apple Maps ID
			"P1352", // Süddeutsche Zeitung topic ID
			"P1358", // AllTrails trail ID
			"P1363", // WorldCat Entities ID
			"P1364", // ScienceDirect topic ID
			"P1367", // Corporate Number (Japan)
			"P1368", // dantai code
			"P1370", // Japan Search name ID
			"P1371", // Proleksis enciklopedija ID
			"P1372", // SBN place ID
			"P1373", // World History Encyclopedia ID
			"P1379", // ANZSRC 2008 FoR ID
			"P1380", // Visuotinė lietuvių enciklopedija ID
			"P1382", // Nomisma ID
			"P1384", // Geni.com profile ID
			"P1385", // CMI person ID
			"P1386", // Union List of Artist Names ID
			"P1387", // Resident Advisor club ID
			"P1388", // MusicBrainz place ID
			"P1393", // ARWU university ID
			"P1394", // QS World University ID
			"P1395", // Times Higher Education World University ID
			"P1396", // U-Multirank university ID
			"P1397", // HAL structure ID
			"P1398", // GEPRIS organization ID
			"P1399", // Legal Entity Identifier
			"P1402", // Proveana ID
			"P1404", // Lokalhistoriewiki.no ID
			"P1405", // Kallías ID
			"P1406", // RePEc institute ID
			"P1407", // ROARMAP ID
			"P1408", // OpenDOAR ID
			"P1409", // elibrary.ru organisation ID
			"P1410", // Gateway to Research organisation ID
			"P1411", // Flanders Arts Institute organisation ID
			"P1412", // LittleSis organization ID
			"P1413", // Schoenberg Database of Manuscripts name ID
			"P1414", // Vatican Library ID (former scheme)
			"P1415", // National Library of Luxembourg ID
			"P1416", // Vatican Library VcBA ID
			"P1418", // National Library of Chile ID
			"P1419", // setlist.fm venue ID
			"P1423", // MSRI institution ID
			"P1425", // Directorio de Museos y Colecciones de España ID
			"P1427", // DIR3 ID
			"P1428", // FragDenStaat public body ID
			"P1430", // CVR number
			"P1434", // Danish educational institution number
			"P1435", // archINFORM project ID
			"P1436", // Trap Danmark ID
			"P1437", // polyDB ID
			"P1450", // zbMATH Keywords
			"P1451", // zbMATH DE Number
			"P1452", // Wikidata LID
			"P1453", // MathRepo page ID
			"P1454", // Software Heritage ID
			"P1468", // Uniform Resource Identifier Scheme
			"P1469", // Rosetta Code page ID
			"P1470", // ONIX codelist ID
			"P1473", // OpenML dataset ID
			"P1496", // DEPRECATED - Internal Project ID
			"P1500", // GEPRIS project ID
			"P1502", // NFDI4Culture ID
			"P1503", // BRUZZ topic ID
			"P1504", // Encyclopedia of Korean Culture ID
			"P1505", // WikiKids ID
			"P1506", // Zenodo communities ID
			"P1511", // MathML-intent-concept-name
			"P1522", // Baidu Tieba name
			"P1525", // Treccani Vocabulary ID
			"P1527", // INAPP Thesaurus ID
			"P1531", // Gran Enciclopèdia Catalana ID
			"P1532", // museum-digital tag ID
			"P1535", // Encyclopedie berbere keyword ID
			"P1538", // Vikidia article ID
			"P1549", // Google Fonts ID
			"P1550", // Google Arts & Culture asset ID
			"P1551", // Google Arts & Culture partner ID
			"P1552", // Google Books ID
			"P1553", // Google Play Movies & TV ID
			"P1554", // Google Scholar paper ID
			"P1555", // Google Scholar case ID
			"P1573", // WikiChip article ID
			"P1574", // C64-Wiki ID
			"P1575", // ProofWiki ID
			"P1579", // Group Properties article ID
			"P1580", // PlanetMath ID
			"P1584", // OEIS ID
			"P1585", // RationalWiki ID
			"P1586", // World of Physics ID
			"P1588", // Metamath statement ID
			"P1589", // Stanford Encyclopedia of Philosophy ID
			"P1590", // PhilPapers topic
			"P1593", // Encyclopedia of Triangle Centers ID
			"P1594", // Parsifal cluster ID
			"P1595" // Great Ukrainian Encyclopedia Online ID
		];
	}
	// https://www.mediawiki.org/wiki/Extension:WikibaseManifest
	## WikibaseManifest Configuration
	wfLoadExtension( 'WikibaseManifest' );
	wfLoadExtension( 'WikibaseFacetedSearch' );
	wfLoadExtension( 'WikibaseExport' );
	wfLoadExtension( 'WikibaseQualityConstraints' );

	$wgWBQualityConstraintsSparqlEndpoint = 'http://query.portal.mardi4nfdi.de/proxy/wdqs/bigdata/namespace/wdq/sparql';
	$wgWBQualityConstraintsInstanceOfId = 'P31';                           // P31
	$wgWBQualityConstraintsSubclassOfId = 'P36';                           // P279
	$wgWBQualityConstraintsPropertyConstraintId = 'P1608';                 // P2302
	$wgWBQualityConstraintsExceptionToConstraintId = 'P1610';              // P2303
	$wgWBQualityConstraintsConstraintStatusId = 'P1611';                   // P2316
	$wgWBQualityConstraintsMandatoryConstraintId = 'Q6486488';             // Q21502408
	$wgWBQualityConstraintsSuggestionConstraintId = 'Q6486489';            // Q62026391
	$wgWBQualityConstraintsDistinctValuesConstraintId = 'Q6486476';        // Q21502410
	$wgWBQualityConstraintsMultiValueConstraintId = 'Q6486494';            // Q21510857
	$wgWBQualityConstraintsUsedAsQualifierConstraintId = 'Q6486499';       // Q21510863
	$wgWBQualityConstraintsSingleValueConstraintId = 'Q6486477';           // Q19474404
	$wgWBQualityConstraintsSymmetricConstraintId = 'Q6486508';             // Q21510862
	$wgWBQualityConstraintsTypeConstraintId = 'Q6486512';                  // Q21503250
	$wgWBQualityConstraintsValueTypeConstraintId = 'Q6486509';             // Q21510865
	$wgWBQualityConstraintsInverseConstraintId = 'Q6486506';               // Q21510855
	$wgWBQualityConstraintsItemRequiresClaimConstraintId = 'Q6486481';     // Q21503247
	$wgWBQualityConstraintsValueRequiresClaimConstraintId = 'Q6486515';    // Q21510864
	$wgWBQualityConstraintsConflictsWithConstraintId = 'Q6486517';         // Q21502838
	$wgWBQualityConstraintsOneOfConstraintId = 'Q6486523';                 // Q21510859
	$wgWBQualityConstraintsMandatoryQualifierConstraintId = 'Q6486487';    // Q21510856
	$wgWBQualityConstraintsAllowedQualifiersConstraintId = 'Q6486524';     // Q21510851
	$wgWBQualityConstraintsRangeConstraintId = 'Q6486529';                 // Q21510860
	$wgWBQualityConstraintsDifferenceWithinRangeConstraintId = 'Q6486526'; // Q21510854
	$wgWBQualityConstraintsCommonsLinkConstraintId = 'Q6486532';           // Q21510852
	$wgWBQualityConstraintsContemporaryConstraintId = 'Q6486534';          // Q25796498
	$wgWBQualityConstraintsFormatConstraintId = 'Q6486483';                // Q21502404
	$wgWBQualityConstraintsUsedForValuesOnlyConstraintId = 'Q6486495';     // Q21528958
	$wgWBQualityConstraintsUsedAsReferenceConstraintId = 'Q6486496';       // Q21528959
	$wgWBQualityConstraintsNoBoundsConstraintId = 'Q6486537';              // Q51723761
	$wgWBQualityConstraintsAllowedUnitsConstraintId = 'Q6486539';          // Q21514353
	$wgWBQualityConstraintsSingleBestValueConstraintId = 'Q6486500';       // Q52060874
	$wgWBQualityConstraintsAllowedEntityTypesConstraintId = 'Q6486510';    // Q52004125
	$wgWBQualityConstraintsCitationNeededConstraintId = 'Q6486549';        // Q54554025
	$wgWBQualityConstraintsPropertyScopeConstraintId = 'Q6486498';         // Q53869507
	$wgWBQualityConstraintsLexemeLanguageConstraintId = 'Q6486556';        // Q55819106
	$wgWBQualityConstraintsLabelInLanguageConstraintId = 'Q6486554';       // Q108139345
	$wgWBQualityConstraintsLanguagePropertyId = 'P1621';                   // P424
	$wgWBQualityConstraintsClassId = 'P924';                               // P2308
	$wgWBQualityConstraintsRelationId = 'P925';                            // P2309
	$wgWBQualityConstraintsInstanceOfRelationId = 'Q56370';                // Q21503252
	$wgWBQualityConstraintsSubclassOfRelationId = 'Q6486474';              // Q21514624
	$wgWBQualityConstraintsInstanceOrSubclassOfRelationId = 'Q78800';      // Q30208840
	$wgWBQualityConstraintsPropertyId = 'P1612';                           // P2306
	$wgWBQualityConstraintsQualifierOfPropertyConstraintId = 'P1613';      // P2305
	$wgWBQualityConstraintsMinimumQuantityId = 'P1614';                    // P2313
	$wgWBQualityConstraintsMaximumQuantityId = 'P1615';                    // P2312
	$wgWBQualityConstraintsMinimumDateId = 'P1616';                        // P2310
	$wgWBQualityConstraintsMaximumDateId = 'P1617';                        // P2311
	$wgWBQualityConstraintsNamespaceId = 'P1618';                          // P2307
	$wgWBQualityConstraintsFormatAsARegularExpressionId = 'P109';          // P1793
	$wgWBQualityConstraintsSyntaxClarificationId = 'P126';                 // P2916
	$wgWBQualityConstraintsConstraintClarificationId = 'P1626';            // P6607
	$wgWBQualityConstraintsConstraintScopeId = 'P1631';                    // P4680
	$wgWBQualityConstraintsConstraintEntityTypesId = 'P1631';              // P4680
	$wgWBQualityConstraintsSeparatorId = 'P1632';                          // P4155
	$wgWBQualityConstraintsConstraintCheckedOnMainValueId = 'Q6486575';    // Q46466787
	$wgWBQualityConstraintsConstraintCheckedOnQualifiersId = 'Q6486574';   // Q46466783
	$wgWBQualityConstraintsConstraintCheckedOnReferencesId = 'Q6486576';   // Q46466805
	$wgWBQualityConstraintsNoneOfConstraintId = 'Q6486521';                // Q52558054
	$wgWBQualityConstraintsIntegerConstraintId = 'Q6486578';               // Q52848401
	$wgWBQualityConstraintsWikibaseItemId = 'Q6486507';                    // Q29934200
	$wgWBQualityConstraintsWikibasePropertyId = 'Q6486548';                // Q29934218
	$wgWBQualityConstraintsWikibaseLexemeId = 'Q6486547';                  // Q51885771
	$wgWBQualityConstraintsWikibaseFormId = 'Q6486546';                    // Q54285143
	$wgWBQualityConstraintsWikibaseSenseId = 'Q6486545';                   // Q54285715
	$wgWBQualityConstraintsWikibaseMediaInfoId = 'Q6486544';               // Q59712033
	$wgWBQualityConstraintsPropertyScopeId = 'P1619';                      // P5314
	$wgWBQualityConstraintsAsMainValueId = 'Q6486550';                     // Q54828448
	$wgWBQualityConstraintsAsQualifiersId = 'Q6486551';                    // Q54828449
	$wgWBQualityConstraintsAsReferencesId = 'Q6486552';                    // Q54828450
}

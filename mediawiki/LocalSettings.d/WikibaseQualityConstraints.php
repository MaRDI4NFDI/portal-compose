<?php

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
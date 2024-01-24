<?php
wfLoadExtension( 'MatomoAnalytics' );
$wgMatomoAnalyticsServerURL='https://matomo.' . getenv('WIKIBASE_HOST') .'/';
$wgMatomoAnalyticsTokenAuth=$_ENV['MATOMO_TOKEN'];

<?php
wfLoadExtension( 'MatomoAnalytics' );
$wgMatomoAnalyticsServerURL='https://matomo.' . getenv('MARDI_HOST') .'/';
$wgMatomoAnalyticsTokenAuth=$_ENV['MATOMO_TOKEN'];

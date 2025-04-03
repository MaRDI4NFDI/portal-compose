<?php
require_once __DIR__ . '/w/maintenance/Maintenance.php';

class AddWiki extends InstallPreConfigured {

}

$maintClass = AddIpfs::class;
/** @noinspection PhpIncludeInspection */
require_once RUN_MAINTENANCE_IF_MAIN;
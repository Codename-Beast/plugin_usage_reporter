<?php
defined('MOODLE_INTERNAL') || die();

// Purge caches
$cache = \cache::make('local_pluginusagereporter', 'plugin_usage');
$cache->purge();
$cache = \cache::make('local_pluginusagereporter', 'api_ratelimit');
$cache->purge();

// Delete all stored reports
global $DB;
$DB->delete_records('pluginusagereporter_reports', []);

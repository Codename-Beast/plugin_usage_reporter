<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/pluginusagereporter:deletereports', $context);

$id = required_param('id', PARAM_INT);

global $DB;

if ($DB->record_exists('pluginusagereporter_reports', ['id' => $id])) {
    $DB->delete_records('pluginusagereporter_reports', ['id' => $id]);

    \local_pluginusagereporter\event\report_deleted::create([
        'objectid' => $id,
        'context' => $context,
        'other' => ['source' => 'user', 'action' => 'deleted'],
    ])->trigger();
}

redirect(new moodle_url('/local/pluginusagereporter/dashboard.php'));

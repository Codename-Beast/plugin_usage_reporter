<?php
require_once(__DIR__ . '/../../config.php');

use local_pluginusagereporter\generator\GeneratorFactory;

require_login();
$context = context_system::instance();
require_capability('local/pluginusagereporter:viewreports', $context);

$id = required_param('id', PARAM_INT);
$format = required_param('format', PARAM_ALPHA);

global $DB;

if (!$record = $DB->get_record('pluginusagereporter_reports', ['id' => $id])) {
    throw new \moodle_exception('errorreportnotfound', 'local_pluginusagereporter');
}

$data = json_decode($record->reportjson, true);

if (empty($data)) {
    throw new \moodle_exception('errornodata', 'local_pluginusagereporter');
}

// Load correct generator
$generator = GeneratorFactory::make($format);

// Set headers
$filename = "plugin_usage_report_{$id}." . strtolower($format);

switch (strtolower($format)) {
    case 'csv':
        header('Content-Type: text/csv');
        break;
    case 'txt':
        header('Content-Type: text/plain');
        break;
    case 'html':
        header('Content-Type: text/html');
        break;
    case 'xml':
        header('Content-Type: application/xml');
        break;
    case 'json':
        header('Content-Type: application/json');
        break;
    default:
        header('Content-Type: application/octet-stream');
}

header("Content-Disposition: attachment; filename={$filename}");

echo $generator->generate($data);
exit;

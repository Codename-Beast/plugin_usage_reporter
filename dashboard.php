<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/pluginusagereporter:viewreports', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/pluginusagereporter/dashboard.php'));
$PAGE->set_title(get_string('pluginusagereporterdashboard', 'local_pluginusagereporter'));
$PAGE->set_heading(get_string('pluginusagereporterdashboard', 'local_pluginusagereporter'));
$PAGE->requires->js_call_amd('local_pluginusagereporter/datatables', 'init');

$output = $PAGE->get_renderer('local_pluginusagereporter');
echo $output->header();

$renderer = new \local_pluginusagereporter\output\dashboard_renderer();
echo $renderer->render_dashboard();

echo $output->footer();

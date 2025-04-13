<?php 
require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_dashboard');

$title = get_string('dashboardtitle', 'local_pluginusagereporter');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo $OUTPUT->render(new \local_pluginusagereporter\output\dashboard());
echo $OUTPUT->footer();
<?php
// Data Collection Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_datacollection');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\datacollection_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('enablelogging', $data->enablelogging, 'local_pluginusagereporter');
    set_config('autodelete', $data->autodelete, 'local_pluginusagereporter');
    set_config('cli_logging', $data->cli_logging, 'local_pluginusagereporter');
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

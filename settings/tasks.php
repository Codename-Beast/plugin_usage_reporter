<?php
// Scheduled Tasks Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_tasks');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\tasks_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('enable_scheduled_task', $data->enable_scheduled_task, 'local_pluginusagereporter');
    set_config('retry_delay', $data->retry_delay, 'local_pluginusagereporter');
    set_config('autodelete_days', $data->autodelete_days, 'local_pluginusagereporter'); // <-- hinzugefügt
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

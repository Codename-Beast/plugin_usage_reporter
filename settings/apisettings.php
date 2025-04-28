<?php
// API Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_apisettings');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\apisettings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('rate_limit', $data->rate_limit, 'local_pluginusagereporter');
    set_config('rate_limit_window', $data->rate_limit_window, 'local_pluginusagereporter');
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

<?php
// Security Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_security');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\security_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('ip_whitelist', $data->ip_whitelist, 'local_pluginusagereporter');
    set_config('admin_override', $data->admin_override, 'local_pluginusagereporter');
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

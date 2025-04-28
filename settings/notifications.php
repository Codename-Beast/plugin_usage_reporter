<?php
// Notification Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_notifications');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\notifications_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('enable_email_notifications', $data->enable_email_notifications, 'local_pluginusagereporter');
    set_config('notification_email', $data->notification_email, 'local_pluginusagereporter');
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

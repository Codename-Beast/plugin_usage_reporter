<?php
// Caching Settings Page for Plugin Usage Reporter
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_pluginusagereporter_caching');

echo $OUTPUT->header();

$mform = new \local_pluginusagereporter\form\caching_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']));
} else if ($data = $mform->get_data()) {
    set_config('enable_caching', $data->enable_caching, 'local_pluginusagereporter');
    set_config('cache_ttl', $data->cache_ttl, 'local_pluginusagereporter');
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_pluginusagereporter']), get_string('changessaved'));
}

// Output the form
$mform->display();
echo $OUTPUT->footer();

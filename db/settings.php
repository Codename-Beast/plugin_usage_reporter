<?php
// This file defines the plugin's main settings entry points for Moodle administration.
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create main category for the plugin under "Local Plugins".
    $ADMIN->add('localplugins', new admin_category('local_pluginusagereporter', get_string('pluginname', 'local_pluginusagereporter')));

    // Subpages / Tabs.
    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_general',
        get_string('tab_general', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/general.php')
    ));
    
    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_datacollection',
        get_string('tab_datacollection', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/datacollection.php')
    ));

    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_caching',
        get_string('tab_caching', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/caching.php')
    ));

    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_tasks',
        get_string('tab_tasks', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/tasks.php')
    ));

    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_apisettings',
        get_string('tab_apisettings', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/apisettings.php')
    ));

    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_security',
        get_string('tab_security', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/security.php')
    ));

    $ADMIN->add('local_pluginusagereporter', new admin_externalpage(
        'local_pluginusagereporter_notifications',
        get_string('tab_notifications', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/settings/notifications.php')
    ));
}

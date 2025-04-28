<?php
// This file is part of the Plugin Usage Reporter - settings configuration
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

//Todo : Splitt settings into multiple files for better maintainability

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Main settings page
    // This page contains the settings for the Plugin Usage Reporter plugin.
    // It allows the administrator to configure various options for the plugin's functionality.
    $settings = new admin_settingpage(
        'local_pluginusagereporter_settings',
        get_string('pluginname', 'local_pluginusagereporter')
    );

    // General Settings
    // This section allows the user to configure general settings for the plugin usage reporter.
    // General settings are essential for the plugin to function correctly and efficiently.
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/general',
        get_string('generalsettings', 'local_pluginusagereporter'),
        ''
    ));
    //Timeframe for report generation
    // This setting allows the user to specify the timeframe for which the plugin usage report should be generated.
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/timeframe',
        get_string('timeframe', 'local_pluginusagereporter'),
        get_string('timeframe_desc', 'local_pluginusagereporter'),
        90,
        PARAM_INT
    ));

    // Data Collection
    // This section allows the user to configure data collection settings for the plugin usage reporter.
    // Data collection settings are crucial for ensuring that the plugin collects the right data for reporting.
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/datacollection',
        get_string('datacollection', 'local_pluginusagereporter'),
        ''
    ));
    // logging level settings 
    // This setting allows the user to choose the level of logging for the plugin usage reporter.
    // Different levels of logging can help in debugging and monitoring the plugin's behavior.
    $settings->add(new admin_setting_configselect(
        'local_pluginusagereporter/logginglevel',
        get_string('logginglevel', 'local_pluginusagereporter'),
        get_string('logginglevel_desc', 'local_pluginusagereporter'),
        'normal',
        [
            'minimal' => get_string('loggingminimal', 'local_pluginusagereporter'),
            'normal' => get_string('loggingnormal', 'local_pluginusagereporter'),
            'verbose' => get_string('loggingverbose', 'local_pluginusagereporter')
        ]
    ));
    // Auto delete days settings
    //after which the old reports will be deleted
    $settings->add(new admin_setting_configtext(
    'local_pluginusagereporter/autodelete_days',
    get_string('autodelete_days', 'local_pluginusagereporter'),
    get_string('autodelete_days_desc', 'local_pluginusagereporter'),
    30
    ));
    // Add a setting to enable or disable logging to the Moodle log
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_clilogging',
        get_string('enable_clilogging', 'local_pluginusagereporter'),
        get_string('enable_clilogging_desc', 'local_pluginusagereporter'),
        1
    ));


    // Caching
    // This section allows the user to configure caching settings for the plugin usage reporter.
    // Caching can improve performance by storing frequently accessed data temporarily.
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/caching',
        get_string('cachingsettings', 'local_pluginusagereporter'),
        get_string('cachingsettings_desc', 'local_pluginusagereporter')
    ));
    // Add a setting to enable or disable caching
    // This setting will allow the user to toggle caching on or off.
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_caching',
        get_string('enablecaching', 'local_pluginusagereporter'),
        get_string('enablecaching_desc', 'local_pluginusagereporter'),
        1
    ));
    // Add a setting for cache TTL (Time to Live)
    // This setting will determine how long the cached data is valid before it needs to be refreshed.
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/cache_ttl',
        get_string('cachettl', 'local_pluginusagereporter'),
        get_string('cachettl_desc', 'local_pluginusagereporter'),
        3600,
        PARAM_INT
    ));

    // 4. Task Settings
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/tasks',
        get_string('tasksettings', 'local_pluginusagereporter'),
        ''
    ));
    // Add a setting to enable or disable the scheduled task
    // This setting will allow the user to toggle the scheduled task on or off.
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_scheduled_task',
        get_string('enablescheduledtask', 'local_pluginusagereporter'),
        get_string('enablescheduledtask_desc', 'local_pluginusagereporter'),
        1
    ));
    // retry delay settings
    // This setting allows the user to specify the delay between retry attempts for the scheduled task.
    $settings->add(new admin_setting_configduration(
        'local_pluginusagereporter/retry_delay',
        get_string('retrydelay', 'local_pluginusagereporter'),
        get_string('retrydelay_desc', 'local_pluginusagereporter'),
        3600, // Default 1 hour
        PARAM_INT
    ));

    // API & Integration Settings
    // This section allows the user to configure API and integration settings for the plugin usage reporter.
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/api',
        get_string('apisettings', 'local_pluginusagereporter'),
        get_string('apisettings_desc', 'local_pluginusagereporter')
    ));
    // Add a setting for the API key
    // This setting will allow the user to specify the API key for external integrations.
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_external_api',
        get_string('enableexternalapi', 'local_pluginusagereporter'),
        get_string('enableexternalapi_desc', 'local_pluginusagereporter'),
        0
    ));
    //external API URL settings
    // This setting allows the user to specify the URL for the external API.
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/external_api_url',
        get_string('externalapiurl', 'local_pluginusagereporter'),
        get_string('externalapiurl_desc', 'local_pluginusagereporter'),
        '',
        PARAM_URL
    ));

    // Security
    // This section allows the user to configure security settings for the plugin usage reporter.
    // Security settings are crucial for protecting sensitive data and ensuring secure communication.
    $settings->add(new admin_setting_heading(
        'local_pluginusagereporter/security',
        get_string('securitysettings', 'local_pluginusagereporter'),
        get_string('securitysettings_desc', 'local_pluginusagereporter')
    ));
    //enforce https settings
    // This setting allows the user to enforce HTTPS for API requests.
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enforce_https',
        get_string('enforcehttps', 'local_pluginusagereporter'),
        get_string('enforcehttps_desc', 'local_pluginusagereporter'),
        1
    ));
    //Key Rotation settings
    // This setting allows the user to specify the key rotation interval for API keys.
    // Key rotation is important for maintaining security and preventing unauthorized access.
    // The default value is set to 30 days (2592000 seconds).
    $settings->add(new admin_setting_configduration(
        'local_pluginusagereporter/key_rotation',
        get_string('keyrotation', 'local_pluginusagereporter'),
        get_string('keyrotation_desc', 'local_pluginusagereporter'),
        2592000, // 30 days
        PARAM_INT
    ));
    //enable debugging settings
    // This setting allows the user to enable or disable debugging for the plugin usage reporter.
    // Debugging can help in identifying issues and monitoring the plugin's behavior.
    // The default value is set to 0 (disabled).

    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_debugging',
        get_string('enabledebug', 'local_pluginusagereporter'),
        get_string('enabledebug_desc', 'local_pluginusagereporter'),
        0
    ));
    //items per page settings
    // This setting allows the user to specify the number of items to display per page in reports.
    // Pagination can improve user experience by breaking down large datasets into manageable chunks.
    // The default value is set to 10 items per page.
    // The parameter type is set to PARAM_INT to ensure that only integer values are accepted.
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/itemsperpage',
        get_string('itemsperpage', 'local_pluginusagereporter'),
        get_string('itemsperpage_desc', 'local_pluginusagereporter'),
        10,
        PARAM_INT
    ));
    

    // Include hidden courses in report settings
    // This setting allows the user to include hidden courses in the plugin usage report.
    // Hidden courses may not be relevant for all reports, so this setting provides flexibility.
    // The default value is set to 0 (disabled).
    // The parameter type is set to PARAM_BOOL to ensure that only boolean values (0 or 1) are accepted.
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/includehidden',
        get_string('includehidden', 'local_pluginusagereporter'),
        get_string('includehidden_desc', 'local_pluginusagereporter'),
        0
    ));

    // --- Notification Settings ---
$settings->add(new admin_setting_heading(
    'local_pluginusagereporter/notificationsettings',
    get_string('notificationsettings', 'local_pluginusagereporter'),
    ''
));

$settings->add(new admin_setting_configcheckbox(
    'local_pluginusagereporter/enable_notifications',
    get_string('enable_notifications', 'local_pluginusagereporter'),
    get_string('enable_notifications_desc', 'local_pluginusagereporter'),
    0
));

$settings->add(new admin_setting_configtext(
    'local_pluginusagereporter/notification_email',
    get_string('notification_email', 'local_pluginusagereporter'),
    get_string('notification_email_desc', 'local_pluginusagereporter'),
    ''
));

    // Add to admin tree
    $ADMIN->add('localplugins', $settings);
}
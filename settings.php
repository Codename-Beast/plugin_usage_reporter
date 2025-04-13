<?php
/**
 * v1.1.1-10 A 2025-04-13 [API Config + Task optional control + Existing settings merged]
 *
 * Plugin settings for the Plugin Usage Reporter.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    //[Since v1] Create main settings page
    $settings = new admin_settingpage(
        'local_pluginusagereporter_settings',
        get_string('pluginname', 'local_pluginusagereporter')
    );

    //[Since v1] Email recipient setting
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/email',
        get_string('emailsetting', 'local_pluginusagereporter'),
        get_string('emailsetting_desc', 'local_pluginusagereporter'),
        '',
        PARAM_EMAIL
    ));

    //[Since v1] Data collection timeframe (in days)
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/timeframe',
        get_string('timeframesetting', 'local_pluginusagereporter'),
        get_string('timeframesetting_desc', 'local_pluginusagereporter'),
        365,
        PARAM_INT
    ));

    //[Since v1] Include hidden courses?
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/includehidden',
        get_string('includehidden', 'local_pluginusagereporter'),
        get_string('includehidden_desc', 'local_pluginusagereporter', '0 = exclude hidden courses, 1 = include them'),
        0
    ));

    //[Since v1] Report generation frequency
    $frequencies = [
        86400   => get_string('daily', 'local_pluginusagereporter'),
        604800  => get_string('weekly', 'local_pluginusagereporter'),
        2592000 => get_string('monthly', 'local_pluginusagereporter')
    ];
    $settings->add(new admin_setting_configselect(
        'local_pluginusagereporter/frequency',
        get_string('frequencysetting', 'local_pluginusagereporter'),
        get_string('frequencysetting_desc', 'local_pluginusagereporter'),
        604800, // Default: weekly
        $frequencies
    ));

    //[Since v1] Report data retention period
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/reporttimeframe',
        get_string('reporttimeframe', 'local_pluginusagereporter'),
        get_string('reporttimeframe_desc', 'local_pluginusagereporter'),
        365,
        PARAM_INT
    ));

    //[Since v1] Materialized View optimization
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/usematerializedview',
        get_string('usematerializedview', 'local_pluginusagereporter'),
        get_string('usematerializedview_desc', 'local_pluginusagereporter'),
        0 // Default: disabled
    ));

    //[Since v1]Email report format selection
    $settings->add(new admin_setting_configselect(
        'local_pluginusagereporter/emailformat',
        get_string('emailformat', 'local_pluginusagereporter'),
        get_string('emailformat_desc', 'local_pluginusagereporter'),
        'html',
        [
            'html' => 'HTML',
            'text' => get_string('textformat', 'local_pluginusagereporter')
        ]
    ));

    // [Since v1.1.1-10 A]: External API export toggle
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_external_api',
        'Enable External API Export',
        'If enabled, plugin usage data will be sent to an external system (e.g., Grafana, central monitoring).',
        0
    ));

    //[Since v1.1.1-10 A]:External API URL
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/external_api_url',
        'External API Endpoint URL',
        'The target endpoint URL for external plugin usage reports.',
        '',
        PARAM_URL
    ));

    //[Since v1.1.1-10 A]: External API Key
    $settings->add(new admin_setting_configpasswordunmask(
        'local_pluginusagereporter/external_api_key',
        'External API Access Key',
        'The API key for authentication with the external system.',
        ''
    ));

    // [Since v1.1.1-10 A]: Scheduled Task enable/disable
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_scheduled_task',
        'Enable Scheduled Report Task',
        'If enabled, plugin usage reports will be generated automatically via Moodle\'s scheduled tasks.',
        0
    ));

    //[Since v1]. Add settings to admin tree
    $ADMIN->add('localplugins', $settings);

    //[Since v1] Add dashboard link to admin navigation
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_pluginusagereporter_dashboard',
        get_string('dashboardtitle', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/dashboard.php'),
        'local/pluginusagereporter:view'
    ));
    //[Since v1] Add API link to admin navigation
    $settings->add(new admin_setting_configtextarea(
        'local_pluginusagereporter/instances',
        'Moodle Instances Configuration',
        'Define instances in JSON format. Example: {"instance1":{"dbhost":"localhost","dbname":"moodle1","dbuser":"user","dbpass":"pass"},"instance2":{"dbhost":"localhost","dbname":"moodle2","dbuser":"user","dbpass":"pass"}}',
        '{}'
    ));

    // [Since v1.1.1-10 E]: Enable email notifications for API / report events
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_notifications',
        'Enable Email Notifications',
        'If enabled, administrators will receive email notifications about report generation and API events.',
        0
    ));
    // [Since v1.1.1-10 G]: Enable or disable caching
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_caching',
        'Enable Caching',
        'Enable or disable data caching for plugin usage reports.',
        1
    ));

    // [Since v1.1.1-10 G]: Cache TTL configuration
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/cache_ttl',
        'Cache Lifetime (seconds)',
        'Time to live (TTL) for cached plugin usage data.',
        3600,
        PARAM_INT
    ));

    // [Since v1.1.1-10 I]: Enable event-based API triggering
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/enable_event_api',
        'Enable Event-based API Trigger',
        'If enabled, plugin usage reports will be automatically triggered by certain Moodle events.',
        0
    ));
}

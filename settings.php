<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin settings for the Plugin Usage Reporter.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // 1. Create main settings page
    $settings = new admin_settingpage(
        'local_pluginusagereporter_settings',
        get_string('pluginname', 'local_pluginusagereporter')
    );

    // 2. Email recipient setting
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/email',
        get_string('emailsetting', 'local_pluginusagereporter'),
        get_string('emailsetting_desc', 'local_pluginusagereporter'),
        '',
        PARAM_EMAIL
    ));

    // 3. Data collection timeframe (in days)
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/timeframe',
        get_string('timeframesetting', 'local_pluginusagereporter'),
        get_string('timeframesetting_desc', 'local_pluginusagereporter'),
        365,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/includehidden',
        get_string('includehidden', 'local_pluginusagereporter'),
        get_string('includehidden_desc', 'local_pluginusagereporter', '0 = exclude hidden courses, 1 = include them'),
        0
    ));

    // 4. Report generation frequency
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

    // 5. Report data retention period
    $settings->add(new admin_setting_configtext(
        'local_pluginusagereporter/reporttimeframe',
        get_string('reporttimeframe', 'local_pluginusagereporter'),
        get_string('reporttimeframe_desc', 'local_pluginusagereporter'),
        365,
        PARAM_INT
    ));

    // 6. Materialized View optimization
    $settings->add(new admin_setting_configcheckbox(
        'local_pluginusagereporter/usematerializedview',
        get_string('usematerializedview', 'local_pluginusagereporter'),
        get_string('usematerializedview_desc', 'local_pluginusagereporter'),
        0 // Default: disabled
    ));

    // 7. Email report format selection
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

    // 8. Add settings to admin tree
    $ADMIN->add('localplugins', $settings);
    
    // 9. Add dashboard link to admin navigation
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_pluginusagereporter_dashboard',
        get_string('dashboardtitle', 'local_pluginusagereporter'),
        new moodle_url('/local/pluginusagereporter/dashboard.php'),
        'local/pluginusagereporter:view'
    ));
}
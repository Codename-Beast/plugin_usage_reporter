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
 * This plugin provides reports on plugin usage for Moodle.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Deprecated cron function.
 * All functionality is implemented in scheduled tasks.
 * This file is kept for compatibility, but contains no executable code.
 *
 * @package local_pluginusagereporter
 *function local_pluginusagereporter_cron(): void {
 *   // Log a message about the scheduled task.
 *  mtrace(get_string('cronjobmessage', 'local_pluginusagereporter'));
*}
*/

/**
 * This function is executed during the plugin's installation.
 * It verifies that the scheduled task is correctly registered.
 *
 * @throws moodle_exception If the scheduled task is not found.
 */
function xmldb_local_pluginusagereporter_install(): void {
    global $DB;

    // Task class name defined in tasks.xml
    $taskname = 'local_pluginusagereporter\task\generate_plugin_usage_report_task';

    // Check if the task is already registered
    $task = $DB->get_record('task_scheduled', ['classname' => $taskname]);

    if (!$task) {
        // Log an error message and throw an exception if the task is not registered
        mtrace(get_string('cronjobnotfound', 'local_pluginusagereporter', $taskname));
        throw new moodle_exception(
            'cronjobnotfound',
            'local_pluginusagereporter',
            '',
            $taskname,
            get_string('cronjobnotfoundexception', 'local_pluginusagereporter')
        );
    } else {
        // Log a success message if the task is registered
        mtrace(get_string('cronjobregistered', 'local_pluginusagereporter', $taskname));
    }
}

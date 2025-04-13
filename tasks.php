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
 * This plugin provides reports on plugin usage for Moodle .
 *
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT 
 */
defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname'   => 'local_pluginusagereporter\task\generate_plugin_usage_report_task',
        'description' => 'Check plugin usage for all plugins/mods in visible courses accessed in the last year.',
        'blocking'    => 0, // Non-blocking task; other tasks can run in parallel.
        'cron'        => '0 0 * * *', // Executes daily at midnight (server timezone).
    ],
];

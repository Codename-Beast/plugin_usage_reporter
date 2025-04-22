<?php
// This file is part of Moodle - http://moodle.org/
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Scheduled task to generate and send plugin usage reports.
 * 
 * Known issues:
 * - The task may not work as expected if the plugin usage data is not available in the database.
 * - The task may fail if the external API is not reachable or returns an error.
 * - The task may block other scheduled tasks if it takes too long to execute. Possible solution: use a cron job to run the task in the background.
**/
namespace local_pluginusagereporter\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use local_pluginusagereporter\external\api_handler;
use local_pluginusagereporter\{ErrorHandler,logger};
use local_pluginusagereporter\datafetcher\RawDataFetcher;

/**
 * Scheduled task to generate and send plugin usage reports.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */
class generate_plugin_usage_report_task extends scheduled_task {

    /**
     * Get task name for admin interface.
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskname', 'local_pluginusagereporter');
    }
    /**
 * Main task execution handler.
 * Fetches plugin usage data, generates a report, saves it, and sends via email.
 * [Since v1.1.1-11 A] Instance logic removed. Executes task for default context only.
 *
 * @return void
 */
public function execute(): void {
    global $DB;

    try {
        $timeframe = (int) get_config('local_pluginusagereporter', 'timeframe');
        $maxRetries = (int) get_config('local_pluginusagereporter', 'retry_attempts') ?: 3;
        $retryDelay = (int) get_config('local_pluginusagereporter', 'retry_delay') ?: 2;

        $fetcher = new RawDataFetcher($DB);
        $data = $fetcher->fetchData($timeframe);
        $datacount = is_array($data) ? count($data) : 0;

        logger::add('success', "Data fetched. Records: {$datacount}", [
            'recordcount' => $datacount
        ]);

        $fetcher->cacheData("plugin_usage_report_task_default", $data, 3600);

        $attempt = 0;
        $success = false;

        if (get_config('local_pluginusagereporter', 'enable_external_api')) {
            $apiHandler = new api_handler();

            while ($attempt < $maxRetries && !$success) {
                try {
                    $attempt++;
                    $success = $apiHandler->send_report($data);

                    if ($success) {
                        logger::add('success', "API report successfully sent.", [
                            'method' => 'scheduled task',
                            'attempt' => $attempt
                        ]);
                        mtrace("API report successfully sent.");
                        break;
                    }

                    mtrace("Attempt {$attempt} failed.");
                } catch (\Throwable $e) {
                    logger::add('error', "Attempt {$attempt} failed. Error: " . $e->getMessage(), [
                        'method' => 'scheduled task',
                        'attempt' => $attempt
                    ]);
                    mtrace("Attempt {$attempt} error - " . $e->getMessage());
                }

                if (!$success && $attempt < $maxRetries) {
                    mtrace("Waiting {$retryDelay} seconds before next attempt...");
                    sleep($retryDelay);
                }
            }

            if (!$success) {
                logger::add('error', "API report failed after all retries.", [
                    'method' => 'scheduled task'
                ]);
                mtrace("API report failed after all retries.");
            }
        }

        mtrace('Plugin Usage Reporter Task: Completed.');

    } catch (\Throwable $e) {
        (new ErrorHandler())->handle($e);
        mtrace('Plugin Usage Reporter Task: Fatal error - ' . $e->getMessage());
    }
}
}

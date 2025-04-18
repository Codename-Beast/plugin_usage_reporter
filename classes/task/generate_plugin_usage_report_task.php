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

namespace local_pluginusagereporter\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use core\message\message;
use moodle_exception;
use stdClass;
use local_pluginusagereporter\external\api_handler;
use local_pluginusagereporter\{ErrorHandler,logger};
use local_pluginusagereporter\datafetcher\{DataFetchInterface, MaterializedViewFetcher, RawDataFetcher};
use local_pluginusagereporter\reportgenerator\{ReportGeneratorInterface, HtmlReportGenerator, TextReportGenerator};

/**
 * Scheduled task to generate and send plugin usage reports.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */
class generate_plugin_usage_report_task extends scheduled_task {


    public function __construct() {
        
    }
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
    * [Since v1.1.1-10 F] Execute the scheduled task with configurable retry mechanism.
    * 
    *
    * @return void
    */
    public function execute(): void
    {
        global $DB;
    
        try {
            $instancesJson = get_config('local_pluginusagereporter', 'instances');
            $instances = !empty($instancesJson) ? json_decode($instancesJson, true) : [];
    
            // If no instances configured, fallback to default
            if (empty($instances)) {
                $instances = ['default' => null];
            }
    
            $maxRetries = (int) get_config('local_pluginusagereporter', 'retry_attempts') ?: 3;
            $retryDelay = (int) get_config('local_pluginusagereporter', 'retry_delay') ?: 2;
    
            foreach ($instances as $instanceName => $instanceConfig) {
                mtrace("Processing instance: {$instanceName}");
                logger::add('success', "Processing instance: {$instanceName}");
    
                $fetcher = new RawDataFetcher($DB);
                if ($instanceName !== 'default') {
                    $fetcher->setInstance($instanceName);
                }
    
                $timeframe = (int) get_config('local_pluginusagereporter', 'timeframe');
                $data = $fetcher->fetch_data($timeframe);
                $datacount = is_array($data) ? count($data) : 0;
    
                logger::add('success', "Data fetched for instance: {$instanceName}. Records: {$datacount}", [
                    'instance' => $instanceName,
                    'recordcount' => $datacount
                ]);
    
                $fetcher->cache_data("plugin_usage_report_task_{$instanceName}", $data, 3600);
    
                $attempt = 0;
                $success = false;
    
                if (get_config('local_pluginusagereporter', 'enable_external_api')) {
                    $apiHandler = new api_handler();
    
                    while ($attempt < $maxRetries && !$success) {
                        try {
                            $attempt++;
                            $success = $apiHandler->send_report($data);
    
                            if ($success) {
                                logger::add('success', "API report successfully sent for instance: {$instanceName}", [
                                    'method' => 'scheduled task',
                                    'attempt' => $attempt,
                                    'instance' => $instanceName
                                ]);
                                mtrace("API report successfully sent for instance: {$instanceName}");
                                break;
                            } else {
                                mtrace("Attempt {$attempt} failed for instance: {$instanceName}");
                            }
                        } catch (\Throwable $e) {
                            logger::add('error', "Attempt {$attempt} failed for instance: {$instanceName}. Error: " . $e->getMessage(), [
                                'method' => 'scheduled task',
                                'attempt' => $attempt,
                                'instance' => $instanceName
                            ]);
                            mtrace("Attempt {$attempt} error for instance: {$instanceName} - " . $e->getMessage());
                        }
    
                        if (!$success && $attempt < $maxRetries) {
                            mtrace("Waiting {$retryDelay} seconds before next attempt...");
                            sleep($retryDelay);
                        }
                    }
                }
    
                if (!$success && get_config('local_pluginusagereporter', 'enable_external_api')) {
                    logger::add('error', "API report failed after all retries for instance: {$instanceName}", [
                        'method' => 'scheduled task',
                        'instance' => $instanceName
                    ]);
                    mtrace("API report failed after all retries for instance: {$instanceName}");
                }
    
                mtrace("Instance {$instanceName} completed.");
            }
    
            mtrace('Plugin Usage Reporter Task: Completed.');
    
        } catch (\Throwable $e) {
            // Handle exceptions and log errors.
            (new ErrorHandler())->handle($e);
            mtrace('Plugin Usage Reporter Task: Fatal error - ' . $e->getMessage());
        }
    }
}

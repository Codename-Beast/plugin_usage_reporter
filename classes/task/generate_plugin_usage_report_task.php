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

namespace local_pluginusagereporter\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use core\message\message;
use moodle_exception;
use stdClass;
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
     */
    public function execute() {
        mtrace("Starting plugin usage report generation at " . date('Y-m-d H:i:s'));
        try {
            // Validate configured email address.
            if (($email_conf = $this->get_validated_email()) === false) {
                return;
            }
            
            // Fetch plugin usage data.
            $data = $this->fetch_usage_data();
            if (empty($data) || !is_array($data)) {
                mtrace(get_string('nodatafound', 'local_pluginusagereporter'));
                error_log("No data found for plugin usage report. Timeframe: " . $this->get_report_timeframe() . " days. Ensure logs exist for this period.");
                return;
            }
            
            // Generate and save the report.
            $report = $this->generate_report($data);
            $this->save_report($data, $this->get_report_timeframe());
            
            // Send report via email.
            $this->send_email_report($email_conf, $report);
        } catch (moodle_exception $e) {
            mtrace("Error during report generation: " . $e->getMessage());
        }
    }
    
    /**
     * Send report via email.
     *
     * @param string $email_conf The recipient email address.
     * @param array $report The generated report data.
     */
    private function send_email_report(string $email_conf, array $report): void {
        $message = new message();
        $message->component = 'local_pluginusagereporter';
        $message->name = 'plugin_usage_report';
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $email_conf;
        $message->subject = get_string('emailsubject', 'local_pluginusagereporter');
        
        // Determine message format (plaintext or HTML).
        $message->fullmessage = $report['format'] === 'text' ? $report['report'] : strip_tags($report['report']);
        $message->fullmessagehtml = $report['format'] === 'html' ? $report['report'] : '';
        $message->fullmessageformat = FORMAT_HTML;
        $message->notification = 1;

        // Attempt to send email and log the result with message_send() return value.
        $result = message_send($message);
        if ($result === false) {
            mtrace(get_string('emailerror', 'local_pluginusagereporter'));
            error_log("Email sending failed for: " . $email_conf . " - Error: " . print_r($message, true));
        } elseif (is_numeric($result) && $result > 0) {
            error_log("Email successfully sent to: " . $email_conf . " - Message ID: " . $result);
        } else {
            error_log("Unexpected response from message_send() for: " . $email_conf . " - Response: " . print_r($result, true));
        }
    }
}

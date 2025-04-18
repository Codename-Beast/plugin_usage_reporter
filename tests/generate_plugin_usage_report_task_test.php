<?php
defined('MOODLE_INTERNAL') || die();

use local_pluginusagereporter\task\generate_plugin_usage_report_task;
use advanced_testcase;

class local_pluginusagereporter_generate_plugin_usage_report_task_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest(true);
    }

    /**
     * Tests the report generation with real data
     * 
     * 1. Create test data
     * 2. Run the task
     * 3. Validate the report data
     */
    public function test_execute() {
        global $DB;

        // 1. Create test data
        $course = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->create_module('assign', ['course' => $course->id]);
        $this->getDataGenerator()->create_module('quiz', ['course' => $course->id]);
        $this->getDataGenerator()->create_module('forum', ['course' => $course->id]);

        // 2. Run the task
        $task = new generate_plugin_usage_report_task();
        $task->execute();

        // 3. Validate the report data
        $reports = $DB->get_records('local_pluginusagereporter_reports');
        $this->assertCount(1, $reports, 'There should be exactly one report generated.');

        $report = reset($reports);
        $data = json_decode($report->report_data);

        // Structure validation
        $this->assertObjectHasAttribute('modulename', $data[0], 'Missing modulename in report.');
        $this->assertObjectHasAttribute('usagecount', $data[0], 'Missing usage count in report.');

        // Data consistency
        $this->assertEquals(3, $data[0]->usagecount, 'Expected 3 plugin instances in the course.');
    }

    /**
     * Tests the email sending with mock
     * 
     * 1. Configure the email mock
     * 2. Run the task
     * 3. Validate the email
     */
    public function test_email_sending(): void {
        // Configure email mock
        unset_config('noemailever');
        $sink = $this->redirectEmails();

        // Run the task
        $task = new generate_plugin_usage_report_task();
        $task->execute();

        // Validate the email
        $emails = $sink->get_messages();
        $this->assertCount(1, $emails, 'There should be exactly one email sent.');

        $email = reset($emails);
        $this->assertStringContainsString(
            get_string('pluginusagereport', 'local_pluginusagereporter'),
            $email->body,
            'The email should contain the report.'
        );
    }
}

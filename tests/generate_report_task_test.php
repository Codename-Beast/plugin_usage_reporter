<?php

declare(strict_types=1);

namespace local_pluginusagereporter\tests;

/**
 * Unit test for scheduled task generate_plugin_usage_report_task.
 *
 * [Since v1.1.1-11 E] Validates that task executes and handles data.
 *
 * @package local_pluginusagereporter
 * @covers \local_pluginusagereporter\task\generate_plugin_usage_report_task
 */

use advanced_testcase;
use local_pluginusagereporter\task\generate_plugin_usage_report_task;

final class generate_report_task_test extends advanced_testcase {

    /**
     * Sets up the test environment.
     *
     * Calls the parent setUp method and resets all changes after the test.
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
    }

    /**
     * Executes the plugin usage report task and verifies that it runs without errors.
     *
     * This test creates a visible course with a quiz module and sets the configuration
     * for the plugin usage reporter. It then executes the scheduled task and ensures
     * there are no assertions, indicating the task executes successfully.
     */

    public function test_execute_task_runs_successfully(): void {
        global $DB;

        $course = $this->getDataGenerator()->create_course(['visible' => 1]);
        $this->getDataGenerator()->create_module('quiz', ['course' => $course->id]);

        set_config('timeframe', 365, 'local_pluginusagereporter');
        set_config('enable_external_api', 0, 'local_pluginusagereporter');

        $task = new generate_plugin_usage_report_task();
        $task->execute();

        $this->expectNotToPerformAssertions();
    }
}

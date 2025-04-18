<?php

declare(strict_types=1);

namespace local_pluginusagereporter\tests;

/**
 * Unit test for report_api_service (Webservice).
 *
 * [Since v1.1.1-11 D] Tests REST API data structure and logic.
 *
 * @package local_pluginusagereporter
 * @covers \local_pluginusagereporter\external\report_api_service
 */

use advanced_testcase;
use local_pluginusagereporter\external\report_api_service;

final class report_api_service_test extends advanced_testcase {

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
     * Tests that get_plugin_usage_data() returns the correct structure.
     *
     * Tests that the function returns an array with the correct keys and that
     * the returned records array has the correct structure.
     *
     * @since v1.1.1-11 D
     */
    public function test_get_plugin_usage_data_returns_correct_structure(): void {
        global $DB;

        $course = $this->getDataGenerator()->create_course(['visible' => 1]);
        $this->getDataGenerator()->create_module('assign', ['course' => $course->id]);

        $result = report_api_service::get_plugin_usage_data(365);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('records', $result);
        $this->assertArrayHasKey('recordcount', $result);
        $this->assertIsArray($result['records']);

        foreach ($result['records'] as $entry) {
            $this->assertArrayHasKey('plugin', $entry);
            $this->assertArrayHasKey('count', $entry);
        }
    }
}

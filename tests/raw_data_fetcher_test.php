<?php

declare(strict_types=1);

namespace local_pluginusagereporter\tests;

/**
 * Unit test for RawDataFetcher.
 *
 * [Since v1.1.1-11 C] Validates logic and data handling.
 *
 * @package local_pluginusagereporter
 * @covers \local_pluginusagereporter\datafetcher\RawDataFetcher
 */

use advanced_testcase;
use local_pluginusagereporter\datafetcher\RawDataFetcher;
use stdClass;

defined('MOODLE_INTERNAL') || die();
/**
 * Summary of raw_data_fetcher_test
 */
final class raw_data_fetcher_test extends advanced_testcase {

    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
    }

/**
 * Tests the logic of fetching data with RawDataFetcher.
 *
 * This test creates 100 test courses and corresponding modules in the database.
 * It then uses the RawDataFetcher to fetch data for the past 365 days.
 * The fetched data is checked to ensure it is an array and not empty.
 * Each record in the fetched data is verified to contain 'plugin' and 'count' keys,
 * and the 'count' value is asserted to be greater than or equal to zero.
 */

    public function test_fetch_data_logic(): void {
        global $DB;

        for ($i = 0; $i < 100; $i++) {
            $course = new stdClass();
            $course->fullname = "Test Course {$i}";
            $course->shortname = "TC{$i}";
            $course->visible = ($i % 2 === 0) ? 1 : 0;
            $course->startdate = time() - (60 * 60 * 24 * 30 * ($i % 12));
            $course->enddate = 0;
            $course->lastaccess = time() - (60 * 60 * 24 * rand(1, 365));
            $courseid = $DB->insert_record('course', $course);

            $module = new stdClass();
            $module->course = $courseid;
            $module->module = 1;
            $module->instance = $i + 1;
            $module->visible = 1;
            $DB->insert_record('course_modules', $module);
        }

        $fetcher = new RawDataFetcher($DB);
        $data = $fetcher->fetch_data(365);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $record) {
            $this->assertArrayHasKey('plugin', $record);
            $this->assertArrayHasKey('count', $record);
            $this->assertGreaterThanOrEqual(0, $record['count']);
        }
    }

    /**
     * Cleans up after each test by removing all test courses.
     *
     * We use a unique course shortname prefix ("TC") to identify test courses.
     *
     * @return void
     */
    protected function tearDown(): void {
        global $DB;
        $DB->delete_records_select('course', "shortname LIKE 'TC%'");
        parent::tearDown();
    }
}

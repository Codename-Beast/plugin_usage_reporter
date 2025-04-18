<?php
/**
 * v1.0.0-10 A 2025-04-13 [Initial Unit Test Creation]
 *
 * Unit tests for RawDataFetcher class.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\tests;

use advanced_testcase;
use local_pluginusagereporter\datafetcher\RawDataFetcher;
use local_pluginusagereporter\ErrorHandler;
use cache;

defined('MOODLE_INTERNAL') || die();

final class raw_data_fetcher_test extends advanced_testcase
{
    /**
     * Reset the database after each test.
     *
     * This method is called automatically by PHPUnit after each test.
     * It resets the database to its initial state, which ensures that
     * each test starts with a clean slate.
     */
    protected function setUp(): void
    {
        $this->resetAfterTest();
    }

    /**
     * Tests that fetch_data() returns an array.
     *
     * This test ensures that the fetch_data() method of RawDataFetcher
     * returns an array when called with a timeframe of 0, indicating no time limit.
     */

    public function test_fetch_data_returns_array(): void
    {
        global $DB;

        $fetcher = new RawDataFetcher($DB, false);
        $result = $fetcher->fetch_data(0); // no time limit

        $this->assertIsArray($result);
    }

    /**
     * Tests if validate_data() returns false for an empty array.
     *
     * Validate_data() should return false if the given data is empty.
     * This test case passes an empty array to validate_data() and asserts
     * the result is false.
     */
    public function test_validate_data_with_empty_array(): void
    {
        global $DB;

        $fetcher = new RawDataFetcher($DB, false);
        $result = $fetcher->validateData([]);

        $this->assertFalse($result);
    }

    /**
     * Tests if transform_data() returns a valid JSON string.
     *
     * @covers \local_pluginusagereporter\datafetcher\RawDataFetcher::transform_data
     */
    public function test_transform_data_json(): void
    {
        global $DB;

        $fetcher = new RawDataFetcher($DB, false);
        $data = [['modulename' => 'book', 'coursename' => 'Test', 'usagecount' => 1]];

        $json = $fetcher->transformData($data, 'json');
        $this->assertJson($json);
    }

    /**
     * Tests that the setPagination() method correctly sets the pagination limit and offset.
     *
     * @covers \local_pluginusagereporter\datafetcher\RawDataFetcher::setPagination
     */
    public function test_pagination_parameters(): void
    {
        global $DB;

        $fetcher = new RawDataFetcher($DB, false);
        $fetcher->setPagination(10, 5);
        $params = (new \ReflectionClass($fetcher))->getProperty('limit');
        $params->setAccessible(true);

        $this->assertEquals(10, $params->getValue($fetcher));
    }

    /**
     * Tests that data is cached and can be retrieved from the cache.
     *
     * @covers \local_pluginusagereporter\datafetcher\RawDataFetcher::cache_data
     * @covers \local_pluginusagereporter\datafetcher\RawDataFetcher::transform_data
     */
    public function test_cache_data_and_retrieve(): void
    {
        global $DB;

        $fetcher = new RawDataFetcher($DB, false);
        $cache = \cache::make('local_pluginusagereporter', 'plugin_usage');

        $key = 'test_cache_key';
        $data = ['modulename' => 'book', 'coursename' => 'Test', 'usagecount' => 1];

        $fetcher->cacheData($key, $data, 60);
        $cached = $cache->get($key);

        $this->assertEquals($data, $cached);
    }
}

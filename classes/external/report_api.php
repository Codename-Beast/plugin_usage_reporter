<?php
/**
 * v1.1.1-10 D 2025-04-13 [Added Rate-Limiting and API Security]
 *
 * REST API for external systems to retrieve plugin usage reports.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\external;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use local_pluginusagereporter\datafetcher\RawDataFetcher;
use moodle_exception;
use local_pluginusagereporter\logger;

class report_api extends external_api
{
    /**
     * [Since v1.0.0-10 A] API parameter definition.
     *
     * @return external_function_parameters
     */
    public static function get_report_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'apikey' => new external_value(PARAM_RAW, 'API key for authentication', VALUE_REQUIRED),
            'timeframe' => new external_value(PARAM_INT, 'Timeframe in days', VALUE_DEFAULT, 365),
            'instance' => new external_value(PARAM_TEXT, 'Optional instance identifier', VALUE_OPTIONAL)
        ]);
    }

    /**
     * [Since v1.0.0-10 A] Retrieve plugin usage report.
     *
     * @param string $apikey
     * @param int $timeframe
     * @param string|null $instance
     * @return array
     * @throws moodle_exception
     */
    public static function get_report(string $apikey, int $timeframe, string $instance = null): array
    {
        global $DB;

        self::validate_parameters(self::get_report_parameters(), [
            'apikey' => $apikey,
            'timeframe' => $timeframe,
            'instance' => $instance
        ]);

        $configuredKey = get_config('local_pluginusagereporter', 'external_api_key');

        if (empty($configuredKey) || $apikey !== $configuredKey) {
            throw new moodle_exception('Invalid API key');
        }

        // [Since v1.1.1-10 D] Rate-Limiting check
        self::check_rate_limit();

        $fetcher = new RawDataFetcher($DB);
        $fetcher->setPagination(1000, 0);

        if (!empty($instance)) {
            $fetcher->setInstance($instance);
        }

        $data = $fetcher->fetch_data($timeframe);

        logger::add('success', 'API report successfully delivered.', [
            'method' => 'REST API',
            'instance' => $instance ?? 'default'
        ]);

        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    /**
     * [Since v1.1.1-10 D] Rate limiting mechanism
     *
     * @return void
     * @throws moodle_exception
     */
    private static function check_rate_limit(): void
    {
        $ip = getremoteaddr();
        $cache = \cache::make('local_pluginusagereporter', 'api_ratelimit');
        $requests = $cache->get($ip) ?: 0;

        // Configurable limit
        $limit = 10; // 10 requests
        $period = 60; // 60 seconds

        if ($requests >= $limit) {
            logger::add('error', 'Rate limit exceeded for IP: ' . $ip);
            throw new moodle_exception('Rate limit exceeded. Please try again later.');
        }

        $cache->set($ip, $requests + 1, $period);
    }

    /**
     * [Since v1.0.0-10 A] Define API response format.
     * Deprecated in favor of the Moodle Webservice API for v2.2
     *
     * @return external_single_structure
     */
    public static function get_report_returns(): external_single_structure
    {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the request'),
            'data' => new external_multiple_structure(
                new external_single_structure([
                    'modulename' => new external_value(PARAM_TEXT, 'Module name'),
                    'coursename' => new external_value(PARAM_TEXT, 'Course name'),
                    'usagecount' => new external_value(PARAM_INT, 'Usage count'),
                    'lastused' => new external_value(PARAM_INT, 'Last usage timestamp'),
                    'user_count' => new external_value(PARAM_INT, 'Number of users'),
                    'roles' => new external_value(PARAM_TEXT, 'Roles of users')
                ])
            )
        ]);
    }
}

<?php

namespace local_pluginusagereporter\external;

/**
 * This file defines the external report API service for Moodle Web Services.
 *
 * Provides a webservice to retrieve plugin usage report data via REST.
 *
 * @since v1.1.1-11 B [Webservice Integration]
 * @package local_pluginusagereporter
 */

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_multiple_structure;
use local_pluginusagereporter\datafetcher\RawDataFetcher;
use required_capability_exception;

class report_api_service extends external_api {

    /**
     * Define expected parameters.
     *
     * @return external_function_parameters
     */
    public static function get_plugin_usage_data_parameters(): external_function_parameters {
        return new external_function_parameters([
            'timeframe'     => new external_value(PARAM_INT, 'Timeframe in days', VALUE_OPTIONAL, 365),
            'pluginfilter'  => new external_value(PARAM_TEXT, 'Filter by plugin name', VALUE_OPTIONAL, ''),
            'limit'         => new external_value(PARAM_INT, 'Limit number of results', VALUE_OPTIONAL, 100),
            'offset'        => new external_value(PARAM_INT, 'Pagination offset', VALUE_OPTIONAL, 0)
        ]);
    }

    /**
     * Main method exposed as webservice.
     *
     * @param int $timeframe
     * @param string $pluginfilter
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function get_plugin_usage_data(int $timeframe = 365, string $pluginfilter = '', int $limit = 100, int $offset = 0): array {
        global $DB;

        self::validate_context(\context_system::instance());

        if (!has_capability('local/pluginusagereporter:view', \context_system::instance())) {
            throw new required_capability_exception(\context_system::instance(), 'local/pluginusagereporter:view', 'nopermissions', '');
        }

        self::validate_parameters(self::get_plugin_usage_data_parameters(), [
            'timeframe' => $timeframe,
            'pluginfilter' => $pluginfilter,
            'limit' => $limit,
            'offset' => $offset
        ]);

        $fetcher = new RawDataFetcher($DB);
        $data = $fetcher->fetch_data($timeframe);

        if ($pluginfilter !== '') {
            $data = array_filter($data, fn($entry) => $entry['plugin'] === $pluginfilter);
        }

        $data = array_values(array_slice($data, $offset, $limit));

        return [
            'status' => 'success',
            'records' => $data,
            'recordcount' => count($data)
        ];
    }

    /**
     * Define return structure.
     *
     * @return external_single_structure
     */
    public static function get_plugin_usage_data_returns(): external_single_structure {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status'),
            'records' => new external_multiple_structure(
                new external_single_structure([
                    'plugin' => new external_value(PARAM_TEXT, 'Plugin name'),
                    'count'  => new external_value(PARAM_INT, 'Usage count')
                ])
            ),
            'recordcount' => new external_value(PARAM_INT, 'Total number of results')
        ]);
    }
}

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
use core_user\external\user_summary_exporter;
use local_pluginusagereporter\datafetcher\RawDataFetcher;

class report_api_service extends external_api {

    /**
     * Returns description of get_plugin_usage_data() parameters.
     *
     * @return external_function_parameters
     */
    public static function get_plugin_usage_data_parameters(): external_function_parameters {
        return new external_function_parameters([
            'timeframe' => new external_value(PARAM_INT, 'Timeframe in days to filter data', VALUE_OPTIONAL, 365)
        ]);
    }

    /**
     * Main function to retrieve plugin usage data.
     *
     * @param int $timeframe
     * @return array
     */
    public static function get_plugin_usage_data(int $timeframe = 365): array {
        global $DB;

        self::validate_parameters(self::get_plugin_usage_data_parameters(), [
            'timeframe' => $timeframe
        ]);

        $fetcher = new RawDataFetcher($DB);
        $data = $fetcher->fetch_data($timeframe);

        return [
            'status' => 'success',
            'records' => $data,
            'recordcount' => count($data)
        ];
    }

    /**
     * Returns structure of get_plugin_usage_data() result.
     *
     * @return external_single_structure
     */
    public static function get_plugin_usage_data_returns(): external_single_structure {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the request'),
            'records' => new external_multiple_structure(
                new external_single_structure([
                    'plugin' => new external_value(PARAM_TEXT, 'Plugin name'),
                    'count' => new external_value(PARAM_INT, 'Usage count')
                ])
            ),
            'recordcount' => new external_value(PARAM_INT, 'Total number of records returned')
        ]);
    }
}

<?php
namespace local_pluginusagereporter\external;

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_multiple_structure;
use local_pluginusagereporter\datafetcher\RawDataFetcher;
use local_pluginusagereporter\exception\report_db_exception;

class report_api_service extends external_api {
    /**
     * Returns description of get_plugin_usage_data() parameters
     *
     * @return external_function_parameters
     */
    public static function get_plugin_usage_data_parameters(): external_function_parameters {
        return new external_function_parameters([
            'timeframe'    => new external_value(PARAM_INT,  'Days to look back', VALUE_OPTIONAL, 365),
            'pluginfilter' => new external_value(PARAM_TEXT, 'Filter by plugin', VALUE_OPTIONAL, ''),
            'limit'        => new external_value(PARAM_INT,  'Limit',            VALUE_OPTIONAL, 100),
            'offset'       => new external_value(PARAM_INT,  'Offset',           VALUE_OPTIONAL, 0),
        ]);
    }

    /**
     * Retrieves plugin usage data.
     *
     * @param int $timeframe Look back by this many days. (optional, default: 365)
     * @param string $pluginfilter Filter records by plugin name. (optional, default: '')
     * @param int $limit Maximum number of records to return. (optional, default: 100)
     * @param int $offset Offset from the beginning of the dataset. (optional, default: 0)
     * @return array
     *   status: Either 'success', 'nodata', or 'error'.
     *   records: An array of plugin usage records, or an empty array if $status is 'nodata'.
     *   recordcount: The number of records in the dataset.
     * @throws external_api::create_service_exception If a database error occurs.
     */
    public static function get_plugin_usage_data(int $timeframe = 365, string $pluginfilter = '', int $limit = 100, int $offset = 0): array {
        global $DB;

        self::validate_context(\context_system::instance());
        require_capability('local/pluginusagereporter:view', \context_system::instance());
        self::validate_parameters(self::get_plugin_usage_data_parameters(), compact('timeframe','pluginfilter','limit','offset'));

        $fetcher = new RawDataFetcher($DB);
        $fetcher->setPagination($limit, $offset);

        try {
            $records = $fetcher->fetchData($timeframe);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        // Empty set.
        if ($records === 'nodata') {
            return ['status' => 'nodata', 'records' => [], 'recordcount' => 0];
        }

        // Optional post‑filter.
        if ($pluginfilter !== '') {
            $records = array_filter($records, fn($r) => $r->modulename === $pluginfilter);
        }

        return [
            'status'      => 'success',
            'records'     => array_values($records),
            'recordcount' => count($records),
        ];
    }

    public static function get_plugin_usage_data_returns(): external_single_structure {
        return new external_single_structure([
            'status'      => new external_value(PARAM_TEXT, 'success|nodata'),
            'records'     => new external_multiple_structure(
                new external_single_structure([
                    'modulename' => new external_value(PARAM_TEXT, 'Plugin/module name'),
                    'coursename' => new external_value(PARAM_TEXT, 'Course'),
                    'usagecount' => new external_value(PARAM_INT,  'Count'),
                    'lastused'   => new external_value(PARAM_INT,  'Unix‑time'),
                ]),
                'Result set'
            ),
            'recordcount' => new external_value(PARAM_INT, 'Number of rows'),
        ]);
    }
}
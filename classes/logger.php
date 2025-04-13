<?php
/**
 * [Initial Logger Class]
 *
 * Logger class for Plugin Usage Reporter.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter;

defined('MOODLE_INTERNAL') || die();

class logger
{
    /**
     * Add a log entry to the database.
     *
     * @param string $eventtype 'success' or 'error'
     * @param string $message Description of the event
     * @param array $data Optional additional data
     * @return void
     */
    public static function add(string $eventtype, string $message, array $data = []): void
    {
        global $DB;

        $record = (object) [
            'eventtime' => time(),
            'eventtype' => $eventtype,
            'message' => $message,
            'additionaldata' => json_encode($data)
        ];

        $DB->insert_record('local_pluginusagereporter_log', $record);
    }

    /**
     * Retrieve the latest log entries.
     *
     * @param int $limit Number of entries to retrieve
     * @return array Log entries
     */
    public static function get_latest(int $limit = 10): array
    {
        global $DB;

        return $DB->get_records('local_pluginusagereporter_log', null, 'eventtime DESC', '*', 0, $limit);
    }

    /**
     * [Since v1.1.1-10 H] Retrieve paginated log entries with optional type filtering.
     *
     * @param string $type Log type filter (success, error, etc.)
     * @param int $limit Number of entries to retrieve
     * @param int $offset Offset for pagination
     * @return array [$logs, $totalcount]
     */
    public static function get_latest_paginated(string $type = '', int $limit = 10, int $offset = 0): array
    {
        global $DB;

        $params = [];
        $wheresql = '';
        if (!empty($type)) {
            $wheresql = 'WHERE eventtype = :type';
            $params['type'] = $type;
        }

        $totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM {local_pluginusagereporter_log} {$wheresql}", $params);

        $logs = $DB->get_records_sql(
            "SELECT * FROM {local_pluginusagereporter_log} {$wheresql} ORDER BY eventtime DESC",
            $params,
            $offset,
            $limit
        );

        return [$logs, $totalcount];
    }

}

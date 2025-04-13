<?php

namespace local_pluginusagereporter\datafetcher;
/**
 * RawDataFetcher class. Fetches raw data without using materialized views.
 * Features:
 * - Cross-database compatible SQL queries
 * - Configurable report parameters
 * - HTML and plaintext report formats
 * - Historical data storage in custom table
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */
defined('MOODLE_INTERNAL') || die();

use moodle_exception;


class RawDataFetcher implements DataFetchInterface {
    /**
     * Fetches raw data without using materialized views.
     * This method executes a query to retrieve plugin usage data, including module names, course names,
     * course IDs, and usage counts for the specified timeframe. The results are filtered to include only
     * course modules that have been accessed within the specified timeframe.
     * @todo Timeframe should be configurable in the plugin settings & Installation, otherwise it should be without limit.
     * @todo Add error handling for database operations.
     * @todo textualize the error messages.
     * @todo Implement a ErrorHandler instant of empty array in return for better error handling.
     * @todo Add logging for debugging purposes.(e.g. using the Moodle logging API)
     * @param int $timeframe The number of days to look back from the current time.
     * @return array An array of records containing plugin usage data.
     * @throws moodle_exception If an error occurs while fetching data.
     * @throws moodle_exception If an error occurs while building the SQL query.
     * @throws moodle_exception If an error occurs while building the query parameters.
     */
    public function fetch_data(int $timeframe): array {
        global $DB;
        try {
            // Build the SQL query to retrieve plugin usage data
            $params = $this->build_query_params($timeframe);
            $sql = $this->build_sql_query($params);
            return $DB->get_records_sql($sql, $params) ?: [];
        } catch (moodle_exception $e) {
            mtrace("RawDataFetcher Error: " . $e->getMessage());
            
            return [];
        }
    }

    /**
     * Builds the SQL parameters for the query.
     * @param int $timeframe The number of days to look back from the current time.
     * @return array The SQL parameters.
     * @throws moodle_exception If an error occurs while building the query parameters.
     */
    private function build_query_params(int $timeframe): array {
        return [
            'starttime' => time() - ($timeframe * 86400),
            'endtime' => time(),
            'includehidden' => (int) get_config('local_pluginusagereporter', 'includehidden'),
            'viewcourse' => '%"function":"core_course_view_course"%',
            'mobilecontent' => '%"function":"tool_mobile_get_content"%'
        ];
    }

    /**
     * Generates the complete SQL statement.
     * @param array $params The SQL parameters.
     * @return string The complete SQL statement.
     * @throws moodle_exception If an error occurs while building the SQL query.
     */
    private function build_sql_query(array $params): string {
        $base_sql = $this->get_base_sql_fragment($params);
        return $base_sql . "
            SELECT 
                m.name AS modulename,
                c.fullname AS coursename,
                COUNT(cm.id) AS usagecount,
                MAX(l.timecreated) AS lastused,
                ud.user_count,
                ud.roles
            FROM {course_modules} cm
            JOIN {modules} m ON cm.module = m.id
            JOIN {course} c ON cm.course = c.id
            LEFT JOIN {logstore_standard_log} l 
                ON l.courseid = c.id 
                AND l.timecreated BETWEEN :starttime AND :endtime
                AND (l.origin = 'ws' OR l.origin = 'mobile')
            LEFT JOIN (
                SELECT courseid, 
                    COUNT(DISTINCT u.id) AS user_count,
                    " . $this->get_role_concat_sql() . " AS roles
                FROM {logstore_standard_log} l
                JOIN {user} u ON l.userid = u.id
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {role} r ON ra.roleid = r.id
                WHERE l.origin = 'ws'
                AND (" . $DB->sql_like('l.other', ':viewcourse') . "
                    OR " . $DB->sql_like('l.other', ':mobilecontent') . ")
                GROUP BY courseid
            ) ud ON ud.courseid = c.id
            WHERE 
                c.visible = :includehidden
                AND cm.added >= :starttime
            GROUP BY c.id, m.id
            ORDER BY usagecount DESC";
    }

    /**
     * Returns the SQL function for concatenating roles.
     * @return string The SQL function for concatenating roles.
     */
    private function get_role_concat_sql(): string {
        global $DB;
        return $DB->sql_group_concat('DISTINCT r.shortname', ', ');
    }

    /**
     * Creates the base SQL fragment for the user data.
     * @param array $params The SQL parameters.
     * @return string The base SQL fragment for the user data.
     * @throws moodle_exception If an error occurs while building the SQL query.
     */
    private function get_base_sql_fragment(array $params): string {
        global $DB;
        return "WITH user_data AS (
            SELECT l.courseid,
                COUNT(DISTINCT u.id) AS user_count,
                " . $this->get_role_concat_sql() . " AS roles
            FROM {logstore_standard_log} l
            JOIN {user} u ON l.userid = u.id
            JOIN {role_assignments} ra ON ra.userid = u.id
            JOIN {role} r ON ra.roleid = r.id
            WHERE l.origin = 'ws'
            AND (" . $DB->sql_like('l.other', ':viewcourse') . "
                OR " . $DB->sql_like('l.other', ':mobilecontent') . ")
            GROUP BY l.courseid
        )";
    }
}
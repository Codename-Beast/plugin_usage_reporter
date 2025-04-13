<?php
/**
 * v1.1.1-10 A 2025-04-13 [Full Interface Compliance + Features]
 *
 * RawDataFetcher class. Fetches raw data without using materialized views.
 *
 * Features:
 * - Cross-database compatible SQL queries
 * - Configurable report parameters
 * - Pagination support
 * - Error handling with custom ErrorHandler class
 * - Moodle Logging API for debugging
 * - Multi-instance support (prepared)
 * - Caching with Moodle Cache API
 * - Data transformation (JSON, text)
 * - Data validation
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\datafetcher;

defined('MOODLE_INTERNAL') || die();

use moodle_exception;
use dml_exception;
use cache;
use local_pluginusagereporter\ErrorHandler;

class RawDataFetcher implements DataFetchInterface
{
    private int $limit = 100;
    private int $offset = 0;
    private ?string $instance = null;
    private cache $cache;
    private ErrorHandler $errorHandler;

    public function __construct(
        private \moodle_database $db,
        private bool $enableTimeLimit = true,
        private int $timeLimitDays = 365
    ) {
        // Init Moodle Cache
        $this->cache = cache::make('local_pluginusagereporter', 'plugin_usage');
        // Init ErrorHandler
        // This is a custom class that handles errors and exceptions in a consistent manner.
        $this->errorHandler = new ErrorHandler();
    }

    /** --------------------
     * Interface Methods
     * -------------------*/

    /**
     * Fetches raw plugin usage data from the database.
     *
     * @param int $timeframe The number of days to look back from the current time.
     * @return array An array of plugin usage records.
     * @throws moodle_exception If an error occurs while fetching data.
     */
    public function fetch_data(int $timeframe): array
    {
        try {
            $params = $this->build_query_params($timeframe);
            $sql = $this->build_sql_query($params);

            $this->log_debug('Executing SQL query', ['sql' => $sql, 'params' => $params]);

            $result = $this->db->get_records_sql($sql, $params);

            return $result ?: [];
        } catch (\Throwable $e) {
            $this->log_error('Fetch data failed', ['error' => $e->getMessage()]);
            $this->errorHandler->handle($e);
            return [];
        }
    }

    /**
     * Applies caching to store and retrieve data efficiently.
     *
     * @param string $key The cache key.
     * @param array $data The data to cache.
     * @param int $ttl Time-to-live for cached data in seconds.
     * @return void
     */
    public function cache_data(string $key, array $data, int $ttl): void
    {
        $this->cache->set($key, $data, $ttl);
        $this->log_debug("Data cached", ['key' => $key, 'ttl' => $ttl]);
    }

    /**
     * Filters the cached plugin usage data based on specified criteria.
     *
     * The method retrieves plugin usage data from the cache and applies the given
     * criteria to filter the data. Each item in the data must match all provided
     * criteria to be included in the result.
     *
     * @param array $criteria Associative array of filter criteria, where keys are
     *                        the field names and values are the expected values
     *                        for those fields.
     * @return array Returns an array of data items that meet the filtering criteria.
     *               If no data is found in the cache or no items match the criteria,
     *               an empty array is returned.
     */

    public function filter_data(array $criteria): array
    {
        $data = $this->cache->get('plugin_usage') ?: [];

        if (empty($data)) {
            $this->log_debug('No data found in cache for filtering');
            return [];
        }

        return array_filter($data, function ($item) use ($criteria) {
            foreach ($criteria as $field => $value) {
                if (!isset($item->$field) || $item->$field != $value) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Transforms the given data into the specified format.
     *
     * @param array $data The data to be transformed.
     * @param string $format The target format for the transformation ('json', 'txt', etc.).
     * @return mixed Returns the transformed data in the specified format or handles an error if the format is unsupported.
     */

    /**
     * Transforms the given data into the specified format.
     *
     * @param array $data The data to be transformed.
     * @param string $format The target format for the transformation ('json', 'txt', etc.).
     * @return mixed Returns the transformed data in the specified format or handles an error if the format is unsupported.
     *
     * Currently supported formats are:
     * - 'json': returns the data as a JSON string using the JSON_PRETTY_PRINT flag.
     * - 'txt': returns the data as a text string where each item is separated by a newline
     *          and each field is separated by a pipe (|) character.
     * @throws moodle_exception Thrown if the given format is not supported.
     */
    public function transform_data(array $data, string $format)
    {
        return match (strtolower($format)) {
            'json' => json_encode($data, JSON_PRETTY_PRINT),
            'txt'  => implode("\n", array_map(fn($item) => implode(' | ', (array)$item), $data)),
            default => $this->errorHandler->handle(new moodle_exception('Unsupported format: ' . $format))
        };
    }

    /**
     * Validates the given data against the required fields.
     *
     * Validation is currently done by checking if the following fields are present in each record:
     * - modulename
     * - coursename
     * - usagecount
     *
     * @param array $data The data to be validated.
     * @return bool Returns true if the data is valid, otherwise false.
     */
    public function validate_data(array $data): bool
    {
        // Check if data is empty
        if (empty($data)) {
            $this->log_debug('Validation failed: data is empty');
            return false;
        }
        // Check if data is an array of objects
        if (!is_array($data) || !isset($data[0]) || !is_object($data[0])) {
            $this->log_debug('Validation failed: data is not an array of objects', $data);
            return false;
        }
        // Check if each record has the required fields
        // This is a simple validation. You can extend it as per your requirements.
        foreach ($data as $record) {
            if (!isset($record->modulename, $record->coursename, $record->usagecount)) {
                $this->log_debug('Validation failed: missing required fields', (array)$record);
                return false;
            }
        }

        $this->log_debug('Data validation passed');
        return true;
    }

    /**
     * Sets pagination parameters.
     *
     * @param int $limit Maximum number of records to retrieve.
     * @param int $offset Offset from the beginning of the dataset.
     * @return self Fluent interface.
     */
    public function setPagination(int $limit, int $offset): self
    {
        // Set the limit for the number of records to retrieve
        $this->limit = $limit; 
        // Set the offset for pagination
        $this->offset = $offset;
        // Log the pagination settings
        $this->log_debug('Pagination set', ['limit' => $limit, 'offset' => $offset]);
        // Return the current instance for method chaining
        // This allows for a fluent interface, enabling method chaining.
        return $this;
    }

    /**
     * Sets the instance name/identifier for multi-instance support.
     *
     * This method is part of the fluent interface and returns the current instance.
     *
     * @param string $instance Instance name/identifier.
     * @return self Fluent interface.
     */
    public function setInstance(string $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    /** --------------------
     * Internal Helpers
     * -------------------*/

    /**
     * Builds the query parameters for the raw data query based on the given parameters.
     *
     * @param int $timeframe The number of days to look back from the current time.
     * @return array Returns an associative array of query parameters.
     */
    private function build_query_params(int $timeframe): array
    {
        return [
            'starttime' => ($timeframe > 0) ? time() - ($timeframe * 86400) : 0,
            'endtime' => time(),
            'includehidden' => (int) get_config('local_pluginusagereporter', 'includehidden'),
            'viewcourse' => '%"function":"core_course_view_course"%',
            'mobilecontent' => '%"function":"tool_mobile_get_content"%',
            'timelimit_enabled' => ($timeframe > 0) ? 1 : 0,
            'timelimit_days' => $timeframe,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    /**
     * Builds the SQL query for fetching raw plugin usage data.
     *
     * The query uses the following tables:
     * - {course_modules} (cm)
     * - {modules} (m)
     * - {course} (c)
     * - {logstore_standard_log} (l)
     * - user_data (ud)
     *
     * The query filters out hidden courses and modules with an add time before the specified start time.
     * The query groups the results by course ID, module ID, user count, and roles.
     * The query orders the results by usage count in descending order.
     * The query limits the number of records to the specified limit and offset.
     *
     * @param array $params An associative array of query parameters.
     * @return string The SQL query string.
     */
    private function build_sql_query(array $params): string
    {
        $baseSql = $this->get_base_sql_fragment($params);

        return $baseSql . "
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
            LEFT JOIN user_data ud ON ud.courseid = c.id
            WHERE 
                c.visible = :includehidden
                AND cm.added >= :starttime
            GROUP BY c.id, m.id, ud.user_count, ud.roles
            ORDER BY usagecount DESC
            LIMIT :limit OFFSET :offset
        ";
    }

    /**
     * Returns an SQL expression for concatenating distinct role shortnames in a group.
     *
     * @return string The SQL expression as a string.
     */
    private function get_role_concat_sql(): string
    {
        return $this->db->sql_group_concat('DISTINCT r.shortname', ', ');
    }

    /**
     * Returns an SQL expression for a Common Table Expression (CTE) that provides user data for each course.
     *
     * The CTE selects the course ID, user count, and a concatenated string of distinct role shortnames
     * for each course. It filters out log records that are not of type 'core_course_view_course' or
     * 'tool_mobile_get_content', and that have a timestamp before the specified start time.
     *
     * @param array $params An associative array of query parameters.
     * @return string The SQL expression as a string.
     */
    private function get_base_sql_fragment(array $params): string
    {
        global $DB;

        return "WITH user_data AS (
            SELECT 
                l.courseid,
                COUNT(DISTINCT u.id) AS user_count,
                " . $this->get_role_concat_sql() . " AS roles
            FROM {logstore_standard_log} l
            JOIN {user} u ON l.userid = u.id
            JOIN {role_assignments} ra ON ra.userid = u.id
            JOIN {role} r ON ra.roleid = r.id
            WHERE l.origin = 'ws'
              AND (" . $DB->sql_like('l.other', ':viewcourse') . "
                   OR " . $DB->sql_like('l.other', ':mobilecontent') . ")
              AND (:timelimit_enabled = 0 OR l.timecreated >= (EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - INTERVAL ':timelimit_days days'))))
            GROUP BY l.courseid
        )";
    }

    /**
     * Logs a debug message with optional data using the Moodle debugging API.
     *
     * @param string $message The debug message to log.
     * @param array $data Optional additional data to log.
     */
    private function log_debug(string $message, array $data = []): void
    {
        debugging($message . ' | ' . json_encode($data), DEBUG_DEVELOPER);
    }

    /**
     * Logs an error message with optional data using the Moodle debugging API.
     *
     * @param string $message The error message to log.
     * @param array $data Optional additional data to log.
     */
    private function log_error(string $message, array $data = []): void
    {
        debugging('ERROR: ' . $message . ' | ' . json_encode($data), DEBUG_DEVELOPER);
    }
}
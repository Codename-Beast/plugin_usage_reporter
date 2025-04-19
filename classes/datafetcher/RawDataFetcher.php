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
 * - Caching with Moodle Cache API
 * - Data transformation (JSON, text)
 * - Data validation
 *
 * 
 * Check if User makes a degative Import to the TimeFrame! so Ban the User !
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 */

namespace local_pluginusagereporter\datafetcher;

defined('MOODLE_INTERNAL') || die();

// Removed moodle_exception as it is not used correctly.
// Removed dml_exception as it is not compatible with Throwable.
use cache;
use local_pluginusagereporter\ErrorHandler;

class RawDataFetcher implements DataFetchInterface
{
    private int $limit = 100;
    private int $offset = 0;
    //private ?string $instance = null;
    private cache $cache;
    private ErrorHandler $errorHandler;

    /**
     * Constructor.
     *
     * @param \moodle_database $db Moodledatabase object
     * @param bool $enableTimeLimit Enable time limit for data fetching (default: true)
     * @param int $timeLimitDays Number of days to limit data fetching (default: 365)
     */
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
     * [Since v1.1.1-10 G] Fetches raw plugin usage data from the database with optional caching.
     *
     * @param int $timeframe The number of days to look back from the current time.
     * @return array An array of plugin usage records.
     * @throws ErrorHandler If an error occurs while fetching data.
     */
    public function fetchData(int $timeframe): array
    {
        // Validate the timeframe parameter to ensure it's not negative.
        // Dirty version will be moved to validateData method
        if ($timeframe <= 0) {
            $this->log_error(get_string('error_invalidtimeframe', 'local_pluginusagereporter') . ': ' . $timeframe);
            $this->errorHandler->handle(new \Exception(get_string('error_invalidtimeframe', 'local_pluginusagereporter')));
            return []; // falls Funktion Daten zurückliefert
        }
        try {
            // Check if caching is enabled
            $cachingEnabled = (bool) get_config('local_pluginusagereporter', 'enable_caching');
            $cacheTTL = (int) get_config('local_pluginusagereporter', 'cache_ttl') ?: 3600;
            // Set the cache key based on the timeframe
            // This allows for different cache entries for different timeframes.
            $cacheKey = 'plugin_usage_' . $timeframe;
            if ($cachingEnabled) {
                $cachedData = $this->cache->get($cacheKey);
                if ($cachedData !== false) {
                    $this->log_debug('Cache hit for key: ' . $cacheKey);
                    return $cachedData;
                }
                $this->log_debug('Cache miss for key: ' . $cacheKey);
            }
            // Build the SQL query and parameters
            // This method constructs the SQL query based on the provided timeframe and other parameters.
            $params = $this->build_query_params($timeframe);
            // Execute the SQL query to fetch raw plugin usage data
            // The query is built using the build_sql_query method, which takes the parameters as input.
            $sql = $this->build_sql_query($params);
            // Log the SQL query and parameters for debugging purposes
            // This is useful for tracking the execution of SQL queries and their parameters.
            $this->log_debug('Executing SQL query', ['sql' => $sql, 'params' => $params]);
            // Execute the SQL query and retrieve the raw plugin usage data
            $result = $this->db->get_records_sql($sql, $params) ?: [];
            // Log the number of records fetched
            $this->log_debug('Fetched ' . count($result) . ' records');
            // If caching is enabled, store the result in the cache with the specified TTL
            // This allows for faster retrieval of data in subsequent requests.
            if ($cachingEnabled) {
                $this->cache->set($cacheKey, $result, $cacheTTL);
                $this->log_debug('Data cached', ['key' => $cacheKey, 'ttl' => $cacheTTL]);
            }

            return $result;
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
    public function cacheData(string $key, array $data, int $ttl): void
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

    public function filterData(array $criteria): array
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
    * [Since v1.1.1-10 C] Transforms the given data into the specified format.
     *
    * @param array $data The data to be transformed.
    * @param string $format The target format for the transformation ('json', 'txt', 'csv', 'xml').
    * @return mixed Returns the transformed data in the specified format or handles an error if the format is unsupported.
    * @throws moodle_exception If the given format is not supported
    */
    public function transformData(array $data, string $format)
    {
        switch (strtolower($format)) {
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT);

            case 'txt':
                return implode("\n", array_map(fn($item) => implode(' | ', (array)$item), $data));

            case 'csv':
                $output = fopen('php://temp', 'r+');
                if (!empty($data)) {
                    // Add headers
                    fputcsv($output, array_keys((array)$data[0]));
                    // Add data rows
                    foreach ($data as $row) {
                        fputcsv($output, (array)$row);
                    }
                }
                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);
                return $csv;

            case 'xml':
                $xml = new \SimpleXMLElement('<report/>');

                foreach ($data as $row) {
                    $entry = $xml->addChild('entry');
                    foreach ((array)$row as $key => $value) {
                        // Clean key name for XML nodes
                        $key = preg_replace('/[^a-z0-9_]/i', '', $key);
                        $entry->addChild($key, htmlspecialchars((string)$value));
                    }
                }

                return $xml->asXML();

            default:
                $this->errorHandler->handle(new \Exception('Unsupported format: ' . $format));
                return null;
        }
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
    public function validateData(array $data): bool
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
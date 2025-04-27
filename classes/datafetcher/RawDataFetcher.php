<?php
/**
 * RawDataFetcher – Retrieves raw plugin usage data from core Moodle tables.
 *
 * @package    Eledia local_pluginusagereporter Plugin
 * @author     Bernd Schreistetter
 * @version    v2.1.1
 *
 * -------------------------------------------------------------------------------------------------
 * Recent Changes (v1.2.1 – 2025-04-21) [Cross-DB Fixes + Runtime Patch]
 * ++ v1.2.1 – 2025‑04‑21 [Cross‑DB Fixes + Runtime Patch]
 * + Improved input validation for timeframe parameter: accepts only values in 1..3650 days (10 year max)
 * + Enforced type safety for critical parameters ($timeframe, $limit, $offset) to prevent query issues
 * + Extended support for output formats in transformData() and robust error handling on invalid formats
 * + Cache key sanitation with regex to avoid buggy/malformed cache access
 * + SQL logic for group_concat+user roles: now DB-agnostic, safely truncated to 255 chars to optimize cross-database compatibility and performance
 * + Centralized and extended developer/debug logging via Moodle's debugging() API
 * + Unified and documented naming and internal property usage ($errorHandler, $lastcachekey)
 * + Refactored and documented constructor (uses dependency injection, prepares cache and error handling)
 * + Strict separation between cache handling, data transformation, and database querying for improved readability and maintainability
 * + Enhanced in-line documentation and code readability throughout the class
 *
 * Known for:
 * - Cross-DB safe SQL for analytics on course/module usage
 * - Centralized caching logic with TTL configuration and customizable storage
 * - Built-in support for pagination, filtering, and modular data transformation (JSON, Text, CSV, XML)
 * - Ready for extension and integration in automated reporting/task runners
 * - PSR-4 compliant class structure for easy integration in Moodle plugin ecosystem
 *
 * TODO / Future Improvements:
 * - Further expand automated test coverage, especially for edge cases and error conditions
 * - Explore async data fetch/caching for very large sites with high traffic and data volumes
 * - Consider implementing a more efficient data structure for caching and filtering (e.g., Redis, Memcached)
 * - Investigate the possibility of using Moodle's built-in caching mechanisms for better performance
 * - Optional: expose cache status and error log to admin dashboards for better monitoring
 * - Optional: add a CLI command for manual cache clearing and data fetching
 * - Optional: add a UI for configuring cache settings and data fetching parameters
 * - Optional: add a UI for configuring data transformation settings (e.g., CSV delimiter, XML root element)
 * - Optional: add a UI for configuring data filtering settings (e.g., filter by course, module, user role)
 * - Optional: add a UI for configuring data export settings (e.g., export to file, send via email)
 * -------------------------------------------------------------------------------------------------
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
    private cache $cache;
    private ErrorHandler $errorHandler;
    /** remembers the key used in the most recent fetchData() call */
    private string $lastcachekey = ''; 

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
     * This function looks back in the database the specified number of days and returns an array of plugin
     * usage records. The records are sorted by the timestamp when the plugin was last used.
     *
     * @param int $timeframe The number of days to look back from the current time.
     * @return array|string An array of plugin usage records.
     * @throws moodle_exception If the timeframe is invalid or if a database error occurs.
     * @throws dml_exception If a database error occurs.
     */
    public function fetchData(?int $timeframe = null, ?int $limit = null, ?int $offset = null): array {
        global $DB, $CACHE;
        //Timeframe is the number of days to look back from the current time.
        $timeframe = $timeframe ?? $this->timeframe;
        //Limit is the maximum number of records to retrieve.
        $limit = $limit ?? $this->limit;
        //Offset is the number of records to skip before starting to retrieve records.
        $offset = $offset ?? $this->offset;
        // Validate the timeframe parameter.
        if ($timeframe === null || $timeframe <= 0 || $timeframe > 3650) {
            throw new \moodle_exception('error_invalidtimeframe', 'local_pluginusagereporter');
        }
        //rawcachekey is the key used to store the data in the cache.
        $rawcachekey = "plugin_usage_{$timeframe}_{$limit}_{$offset}";
        //Sanitize the cache key to avoid any special characters that could cause issues.
        $cachekey = preg_replace('/[^a-zA-Z0-9_]/', '', $rawcachekey);
        $this->lastcachekey = $cachekey;
        // Check if the cache is enabled and if the data is already cached.
        $this->cache = $CACHE->get_cache('local_pluginusagereporter', 'plugin_usage');
        // Check if caching is enabled in the plugin settings.
        $cachingenabled = (bool)get_config('local_pluginusagereporter', 'enable_caching');
        $cachettl = (int)get_config('local_pluginusagereporter', 'cache_ttl') ?: 3600;
        // If caching is enabled and the data is already cached, return the cached data.
        if ($cachingenabled && ($cached = $this->cache->get($cachekey)) !== false) {
            return [
                'status' => empty($cached) ? 'nodata' : 'ok',
                'records' => $cached,
                'recordcount' => count($cached)
            ];
        }
        // If the data is not cached, fetch it from the database.
        // build_query_params() returns an array of parameters for the SQL query.
        $params = $this->build_query_params($timeframe);
        // build_sql_query() returns the SQL query to retrieve the raw data for the report.
        $sql = $this->build_sql_query();
        // try to fetch the data from the database.
        // If an error occurs, log it and throw a moodle_exception.
        // The $params array contains the parameters for the SQL query.
        try {
            $records = $DB->get_records_sql($sql, $params, $offset, $limit);
        } catch (\dml_exception $e) {
            throw new \moodle_exception('error_db', 'local_pluginusagereporter', '', null, $e->getMessage());
        }
        // If no records are found, log it and return an empty array.
        if (empty($records)) {
            $this->log_debug('No Data has been found', ['records' => $records]);
            return [
                'status' => 'nodata',
                'records' => [],
                'recordcount' => 0
            ];
        }
        // If records are found, log the number of records and the cache key used.
        $records = array_values($records);

        if ($cachingenabled) {
            $this->cache->set($cachekey, $records, $cachettl);
        }

        return [
            'status' => 'ok',
            'records' => $records,
            'recordcount' => count($records)
        ];
    }

    /**
     * Fetches detailed plugin usage data including user counts and roles.
     *
     * @param int|null $starttime Start timestamp for data retrieval.
     * @param int|null $endtime End timestamp for data retrieval.
     * @return array Returns detailed plugin usage data.
     * @throws \moodle_exception If database error occurs.
    */
    public function fetchExtendedData(?int $starttime = null, ?int $endtime = null): array {
        global $DB;

        $starttime = $starttime ?? (time() - (30 * DAYSECS)); // Default: last 30 days
        $endtime = $endtime ?? time();

        // Check DB type first.
        $dbfamily = $DB->get_dbfamily(); // Returns 'mysql', 'postgres', etc.

        if (!in_array($dbfamily, ['mysql', 'postgres'])) {
            throw new \moodle_exception('error_db_unsupported', 'local_pluginusagereporter');
        }

        // Check if MySQL < 8 (doesn't support CTEs properly)
        if ($dbfamily === 'mysql' && version_compare($DB->get_server_info(), '8.0', '<')) {
            throw new \moodle_exception('error_db_requires_mysql8', 'local_pluginusagereporter');
        }

        $params = [
            'starttime' => $starttime,
            'endtime' => $endtime,
            'viewcourse' => '%view course%',
            'mobilecontent' => '%mobile_content%',
            'includehidden' => 1 // Example: modify if needed
        ];

        $sql = "
            WITH user_data AS (
                SELECT
                    l.courseid,
                    COUNT(DISTINCT u.id) AS user_count,
                    GROUP_CONCAT(DISTINCT r.shortname ORDER BY r.shortname ASC SEPARATOR ', ') AS roles
                FROM {logstore_standard_log} l
                INNER JOIN {user} u ON u.id = l.userid
                INNER JOIN {role_assignments} ra ON ra.userid = u.id
                INNER JOIN {role} r ON r.id = ra.roleid
                WHERE l.origin IN ('web', 'mobile')
                AND l.timecreated BETWEEN :starttime AND :endtime
                GROUP BY l.courseid
            )
            
            SELECT
                m.name AS modulename,
                c.fullname AS coursename,
                COUNT(cm.id) AS usagecount,
                MAX(l.timecreated) AS lastused,
                ud.user_count,
                ud.roles
            FROM {course_modules} cm
            INNER JOIN {modules} m ON m.id = cm.module
            INNER JOIN {course} c ON c.id = cm.course
            LEFT JOIN (
                SELECT
                    l.courseid,
                    l.timecreated
                FROM {logstore_standard_log} l
                WHERE l.timecreated BETWEEN :starttime AND :endtime
                AND (l.other LIKE :viewcourse OR l.other LIKE :mobilecontent)
            ) l ON l.courseid = c.id
            LEFT JOIN user_data ud ON ud.courseid = c.id
            WHERE
                (:includehidden = 1 OR c.visible = 1)
                AND cm.added >= :starttime
            GROUP BY
                m.name,
                c.fullname,
                ud.user_count,
                ud.roles
            ORDER BY
                usagecount DESC
        ";

        try {
            $records = $DB->get_records_sql($sql, $params);
        } catch (\dml_exception $e) {
            throw new \moodle_exception('error_db', 'local_pluginusagereporter', '', null, $e->getMessage());
        }

        return array_values($records ?: []);
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
    * [Since v1.1.1-10 C] Transforms the given data into the specified format.
     *
    * @param array $data The data to be transformed.
    * @param string $format The target format for the transformation ('json', 'txt', 'csv', 'xml').
    * @return mixed Returns the transformed data in the specified format or handles an error if the format is unsupported.
    * @throws moodle_exception If the given format is not supported
    */
    public function transformData(array $data, string $format) : mixed
    {
        if (!in_array(strtolower($format), ['json', 'txt', 'csv', 'xml'])) {
            	throw new moodle_exception('error_invalidformat', 'local_pluginusagereporter');
        }
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
                // Handle unsupported format
                $this->log_debug('Unsupported format requested', ['format' => $format]);
                throw new moodle_exception('error_invalidformat',  'local_pluginusagereporter', '', null, $format);

        }
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
    public function filterData(?string $pluginfilter = null, ?int $minusagecount = null): array {
        global $CACHE; // Moodle Cache API.

        if (empty($this->lastcachekey)) {
            // No cache key available, return empty result.
            return [];
        }

        $cache = $CACHE->get_cache('local_pluginusagereporter', 'plugin_usage');
        $cachedData = $cache->get($this->lastcachekey);

        if ($cachedData === false) {
            // Cache miss, no data available.
            return [];
        }

        // If no filters are applied, return all cached data.
        if ($pluginfilter === null && $minusagecount === null) {
            return $cachedData;
        }

        // Apply filters.
        $filteredData = array_filter($cachedData, function($record) use ($pluginfilter, $minusagecount) {
            if (!is_array($record)) {
                return false;
            }

            $matchesPlugin = true;
            if ($pluginfilter !== null && isset($record['modulename'])) {
                $matchesPlugin = stripos($record['modulename'], $pluginfilter) !== false;
            }

            $matchesUsage = true;
            if ($minusagecount !== null && isset($record['usagecount'])) {
                $matchesUsage = (int)$record['usagecount'] >= $minusagecount;
            }

            return $matchesPlugin && $matchesUsage;
        });

        return array_values($filteredData); // Reset array keys.
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
     * Builds an array of parameters for the SQL query to retrieve the raw data for the report.
     *
     * The returned array contains the following parameters:
     * - starttime: The timestamp of the start of the given timeframe.
     * - endtime: The timestamp of the end of the given timeframe.
     * - includehidden: A flag indicating whether to include hidden courses in the report.
     * - viewcourse: A SQL pattern to match the 'core_course_view_course' function in the logs.
     * - mobilecontent: A SQL pattern to match the 'tool_mobile_get_content' function in the logs.
     *
     * @param int $timeframe The number of days to look back from the current time.
     *
     * @return array An array of parameters to be used in the SQL query.
     */
    private function build_query_params(int $timeframe): array {
        // Validate the timeframe parameter
        if ($timeframe === null || $timeframe <= 0 || $timeframe > 3650) {
            throw new \moodle_exception('error_invalidtimeframe', 'local_pluginusagereporter');
        }

        $starttime = time() - ($timeframe * DAYSECS);
        return [
            'starttime'     => $starttime,
            'endtime'       => time(),
            'includehidden' => (int) get_config('local_pluginusagereporter', 'includehidden'),
            'viewcourse'    => '%' . $this->db->sql_like_escape('"function":"core_course_view_course"') . '%',
            'mobilecontent' => '%' . $this->db->sql_like_escape('"function":"tool_mobile_get_content"') . '%',// cosmetic
        ];
    }

    /**
     * Builds the SQL query for retrieving the raw data for the report.
     *
     * The query retrieves the following data for each course and module:
     * - modulename
     * - coursename
     * - usagecount: The number of times the module has been used in the course.
     * - lastused: The timestamp of the most recent use of the module in the course.
     * - user_count: The number of users enrolled in the course.
     * - roles: The roles of the users enrolled in the course.
     *
     * The query filters out courses that are not visible or have not been used
     * in the given timeframe. It also filters out modules that are not of type
     * 'course' or have not been used in the given timeframe.
     *
     * The query uses a common table expression (CTE) to retrieve the per-course
     * user summary. The CTE is then joined with the course and module tables to
     * retrieve the module usage data.
     *
     * @return string The SQL query as a string.
     */
    private function build_sql_query(): string {
        $likeview   = $this->db->sql_like('l.other', ':viewcourse', false);
        $likemobile = $this->db->sql_like('l.other', ':mobilecontent', false);

        return $this->get_base_sql_fragment() . "
            SELECT m.name AS modulename,
                   c.fullname AS coursename,
                   COUNT(cm.id) AS usagecount,
                   MAX(l.timecreated) AS lastused,
                   ud.user_count,
                   ud.roles
              FROM {course_modules} cm
              JOIN {modules} m ON m.id = cm.module
              JOIN {course}  c ON c.id = cm.course
         LEFT JOIN {logstore_standard_log} l
                    ON l.courseid = c.id
                   AND l.timecreated BETWEEN :starttime AND :endtime
                   AND ({$likeview} OR {$likemobile})
         LEFT JOIN user_data ud ON ud.courseid = c.id
             WHERE (:includehidden = 1 OR c.visible = 1)
               AND cm.added >= :starttime
          GROUP BY c.id, m.id, ud.user_count, ud.roles
          ORDER BY usagecount DESC";
    }

 
    /**
     * Returns a SQL query fragment for a common table expression (CTE) that
     * retrieves a summary of users enrolled in each course, including the
     * number of users and their roles.
     *
     * The CTE filters out log entries that are not of type 'view' or 'mobile' or
     * that do not have a timestamp within the given timeframe.
     *
     * The CTE joins the log table with the user, role assignment, and role
     * tables to retrieve the desired user data.
     *
     * @return string The SQL query fragment as a string.
     */
    private function get_base_sql_fragment(): string {
        $likeview   = $this->db->sql_like('l.other', ':viewcourse', false);
        $likemobile = $this->db->sql_like('l.other', ':mobilecontent', false);

        return "WITH user_data AS (
            SELECT l.courseid,
                   COUNT(DISTINCT u.id) AS user_count,
                   " . $this->get_role_concat_sql() . " AS roles
              FROM {logstore_standard_log} l
              JOIN {user} u ON u.id = l.userid
              JOIN {role_assignments} ra ON ra.userid = u.id
              JOIN {role} r ON r.id = ra.roleid
             WHERE l.origin IN ('web','mobile')
               AND ({$likeview} OR {$likemobile})
               AND l.timecreated >= :starttime
             GROUP BY l.courseid
        )
        ";
    }

   /**
     * Returns an SQL expression for concatenating distinct role shortnames in a group,
     * truncated on SQL level to max. 255 characters for compatibility and performance.
     *
     * @return string The SQL expression as a string.
     */
    private function get_role_concat_sql(): string
    {
        // SQL truncation, Moodle-DB abstraction: use LEFT() (MySQL), SUBSTR() (Postgres, Oracle), etc.
        // Moodle's $DB->sql_substr() gives you the right substring statement, platform-independently.
        // $this->db->sql_group_concat(expr, sep, orderby = null)
        $concat_expr = $this->db->sql_group_concat('DISTINCT r.shortname', ', ');
        // Wrap in SQL SUBSTR to truncaten (hier: 255 Zeichen)
        return $this->db->sql_substr("($concat_expr)", 1, 255);
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
}
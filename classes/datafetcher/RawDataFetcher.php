<?php
/**
 * v1.2.1 – 2025‑04‑21 [Cross‑DB Fixes + Runtime Patch]
 *
 * RawDataFetcher – fetches raw plugin‑usage data directly from core tables.
 *
 * Key fixes since v1.1.1‑10:
 * - Added missing moodle_exception & dml_exception imports
 * - Escaped LIKE wildcards with sql_like_escape() + case‑insensitive search
 * - Removed unused INTERVAL & LIMIT/OFFSET placeholders (paging via DML helper)
 * - Corrected visibility logic (includehidden flag)
 * - Unified $errorHandler property name
 * - Eliminated unused query parameters
 * - Single DB handle ($this->db) throughout
 *
 * @package    local_pluginusagereporter
 * @author     Bernd Schreistetter
 * 
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
    private ?string $lastcachekey = null;   

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
     * @return array|string An array of plugin usage records.
     * @throws moodle_exception Exception If the timeframe is invalid or if a database error occurs.
     * @throws dml_exception If a database error occurs.
     */
    public function fetchData(int $timeframe): array|string
    {
        // ---  Validate input --//
        if ($timeframe <= 0) {
             throw new moodle_exception('error_invalidtimeframe', 'local_pluginusagereporter','', null, $timeframe);
        }
        

        // --- Cache handling ----//
        $this->lastcachekey = $cachekey = "plugin_usage_{$timeframe}_{$this->limit}_{$this->offset}";
        $cachingenabled = (bool) get_config('local_pluginusagereporter', 'enable_caching');
        $cachettl       = (int) get_config('local_pluginusagereporter', 'cache_ttl') ?: 3600;

        if ($cachingenabled && ($cached = $this->cache->get($cachekey)) !== false) {
            return $cached ?: 'nodata';
        }

        // --- Build query -----//
        $params = $this->build_query_params($timeframe);
        $sql    = $this->build_sql_query();

        // --- Execute query -----//
        try {
            // pass limit/offset via DML helper – cross‑DB compatible
            $records = $this->db->get_records_sql($sql, $params, $this->offset, $this->limit);
        } catch (dml_exception $e) {
            // Catch dml_exception and re‑throw as moodle_exception so WS & CLI handle it natively
            throw new moodle_exception(
                'error_db',
                'local_pluginusagereporter',
                '',
                null,
                $e->getMessage()
            );
        }

        // ---  Post‑processing ---- //
        if (empty($records)) {
            $this->log_debug("No Data has been found", ['records' => $records]);
            return 'nodata';
        }

        $records = array_values($records);

        if ($cachingenabled) {
            $this->cache->set($cachekey, $records, $cachettl);
        }
        return $records;
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
    public function filterData(array $criteria = []): array {
        //  Determine the most recent cache‑key that fetchData() used.
        //  Fallback: legacy static key, so older caches remain nutzbar.
        $cachekey = $this->lastcachekey
            ?? "plugin_usage_{$this->limit}_{$this->offset}";

        $data = $this->cache->get($cachekey) ?: [];
        if (empty($data) || empty($criteria)) {
            // nothing cached or no filter requested → return as is / empty.
            $this->log_debug('filterData(): no cached data or no criteria', [
                'key' => $cachekey,
                'criteria' => $criteria,
            ]);
            return $data;
        }

        // Apply AND‑filter: every criterion must match.
        $result = array_values(array_filter($data, function ($row) use ($criteria) {
            foreach ($criteria as $field => $expected) {
                if (!property_exists($row, $field) || $row->$field != $expected) {
                    return false;
                }
            }
            return true;
        }));

        $this->log_debug('filterData(): filter complete', [
            'key'       => $cachekey,
            'criteria'  => $criteria,
            'hits'      => count($result),
            'total'     => count($data),
        ]);

        return $result;
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
             WHERE l.origin IN ('ws','mobile')
               AND ({$likeview} OR {$likemobile})
               AND l.timecreated >= :starttime
             GROUP BY l.courseid
        )
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
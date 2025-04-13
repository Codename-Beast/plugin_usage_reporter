<?php 
namespace local_pluginusagereporter\datafetcher;

use local_pluginusagereporter\datafetcher\DataFetchInterface;
use moodle_exception;
class MaterializedViewFetcher implements DataFetchInterface {
/**
 * Scheduled task to generate and send plugin usage reports.
 * Features:
 * - Cross-database compatible SQL queries
 * - Configurable report parameters
 * - HTML and plaintext report formats
 * - Historical data storage in custom table
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */
/**
 * Fetches data from the materialized view within the specified timeframe.
 *
 * This method refreshes the materialized view and then executes a query
 * to retrieve plugin usage data, including module names, course names,
 * usage count, last used timestamp, user count, and roles associated
 * with the usage. The results are filtered to include only records within
 * the timeframe defined by the start and end time parameters.
 *
 * @param int $timeframe The number of days to look back from the current time.
 * @return array An array of records from the materialized view.
 * @throws moodle_exception If an error occurs while fetching data.
 */

    /**
     * Fetches data from the materialized view within the specified timeframe.
     *
     * @param int $timeframe The number of days to look back from the current time.
     * @return array An array of records from the materialized view.
     * @throws moodle_exception If an error occurs while fetching data.
     */
    public function fetch_data(int $timeframe): array {
        global $DB;

        try {
            $this->refresh_materialized_view();
            $params = [
                'starttime' => time() - ($timeframe * 86400),
                'endtime' => time()
            ];
            
            $sql = "SELECT modulename, coursename, usagecount, lastused, user_count, roles
                    FROM {pluginusagereporter_view}
                    WHERE lastused BETWEEN :starttime AND :endtime
                    ORDER BY usagecount DESC";
            
            return $DB->get_records_sql($sql, $params) ?: [];
        } catch (moodle_exception $e) {
            throw new moodle_exception('materializedview_error', 'local_pluginusagereporter');
        }
    }

    /**
     * Refreshes the materialized view, which is required for PostgreSQL only.
     *
     * This method executes a REFRESH MATERIALIZED VIEW query to update the
     * contents of the materialized view. This is necessary because the view is
     * not updated automatically when the underlying tables change.
     * 
     */
    private function refresh_materialized_view(): void {
        try {
            global $DB;
            if ($DB->get_dbfamily() === 'postgres') {
                $DB->execute("REFRESH MATERIALIZED VIEW {pluginusagereporter_view}");
            }
        } catch (moodle_exception $e) {
            throw new moodle_exception('materializedview_error', 'local_pluginusagereporter');
            mtrace("MaterializedViewFetcher Error: " . $e->getMessage());
        }
    }
}
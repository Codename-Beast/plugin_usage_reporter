<?php 
namespace local_pluginusagereporter\datafetcher;
/**
 *  * Interface DataFetchInterface
 * Version: 1.1
 * 
 * Defines the structure for fetching and processing plugin usage data.
 * Includes methods for data fetching, caching, filtering, and transformation.
 *
 * Scheduled task to generate and send plugin usage reports.
 * Features:
 * - Cross-database compatible SQL queries
 * - Configurable report parameters
 * - HTML and plaintext report formats
 * - Historical data storage in custom table
 * - @todo add more features like:
 * - Error handling for database operations
 * - Logging for debugging purposes (e.g. using the Moodle logging API)
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */
interface DataFetchInterface {
    public function fetch_data(int $timeframe): array;
      /**
     * Applies caching to store and retrieve data efficiently.
     * 
     * @param string $key The cache key.
     * @param array $data The data to cache.
     * @param int $ttl Time-to-live for cached data in seconds.
     * @return void
     * 
     * Example usage:
     * $dataFetch->cache_data('plugin_usage', $data, 3600);
     */
    public function cache_data(string $key, array $data, int $ttl): void;

    /**
     * Filters the plugin data based on custom criteria.
     * 
     * @param array $criteria An associative array of filter criteria.
     * @return array Returns filtered plugin data.
     * 
     * Example usage:
     * $filteredData = $dataFetch->filter_data(['course_id' => 23]);
     */
    public function filter_data(array $criteria): array;

    /**
     * Transforms raw data into the desired format (e.g., JSON, XML, CSV).
     * 
     * @param array $data The raw data to transform.
     * @param string $format The desired output format.
     * @return mixed Returns data in the specified format.
     * @todo Add support for more formats like CSV, etc.
     * @todo Add error handling for unsupported formats.
     * Example usage:
     * $jsonData = $dataFetch->transform_data($data, 'json');
     */
    public function transform_data(array $data, string $format);
    
    /**
     * Validates the retrieved data to ensure it meets required standards.
     * 
     * @param array $data The data to validate.
     * @return bool Returns true if data is valid, otherwise false.
     * 
     * Example usage:
     * if ($dataFetch->validate_data($data)) {
     *     // Data is valid
     * }
     */
    public function validate_data(array $data): bool;
}

<?php
/**
 * v1.2.0-10 A 2025-04-13 [Extension for Pagination, Multi-Instance, ErrorHandling]
 *
 * DataFetchInterface defines the structure for fetching and processing plugin usage data.
 *
 * Includes:
 * - Data fetching
 * - Caching
 * - Filtering
 * - Transformation (output formats)
 * - Validation
 * - NEW: Pagination support
 * - NEW: Multi-instance support
 * - NEW: ErrorHandler integration
 *
 * Scheduled task to generate and send plugin usage reports.
 * Features:
 * - Cross-database compatible SQL queries
 * - Configurable report parameters
 * - HTML and plaintext report formats
 * - Historical data storage in custom table
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\datafetcher;

interface DataFetchInterface
{
    /**
     * Fetches plugin usage data.
     *
     * @param int $timeframe The number of days to look back.
     * @return array Fetched plugin usage data.
     */
    public function fetch_data(int $timeframe): array;

    /**
     * Applies caching to store and retrieve data efficiently.
     *
     * @param string $key The cache key.
     * @param array $data The data to cache.
     * @param int $ttl Time-to-live for cached data in seconds.
     * @return void
     */
    public function cache_data(string $key, array $data, int $ttl): void;

    /**
     * Filters the plugin data based on custom criteria.
     *
     * @param array $criteria Associative array of filter criteria.
     * @return array Returns filtered plugin data.
     */
    public function filter_data(array $criteria): array;

    /**
     * Transforms raw data into the desired format (e.g., JSON, XML, CSV, Plaintext).
     *
     * @param array $data The raw data to transform.
     * @param string $format The desired output format ('json', 'csv', 'txt', etc.).
     * @return mixed Returns data in the specified format.
     */
    public function transform_data(array $data, string $format);

    /**
     * Validates the retrieved data to ensure it meets required standards.
     *
     * @param array $data The data to validate.
     * @return bool Returns true if data is valid, otherwise false.
     */
    public function validate_data(array $data): bool;

    /**
     * Sets pagination parameters.
     *
     * @param int $limit Maximum number of records to retrieve.
     * @param int $offset Offset from the beginning of the dataset.
     * @return self Fluent interface.
     */
    public function setPagination(int $limit, int $offset): self;

    /**
     * Sets instance name for multi-instance support.
     *
     * @param string $instance Instance name/identifier.
     * @return self Fluent interface.
     */
    public function setInstance(string $instance): self;
}

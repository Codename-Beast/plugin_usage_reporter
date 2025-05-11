<?php
// CLI script for Plugin Usage Reporter (eledia_usagereporter)
// Usage: php admin/cli/plugin_usage_report.php --timeframe=30 [--limit=100] [--offset=0] [--format=json|csv|txt|xml] [--filter=mod_assign]
// Must be run as a Moodle admin user.

// phpcs:ignoreFile
define('CLI_SCRIPT', true);
require(__DIR__ . '/../../config.php');

require_once($CFG->dirroot . '/local/pluginusagereporter/classes/datafetcher/RawDataFetcher.php');

use local_pluginusagereporter\datafetcher\RawDataFetcher;

// CLI options
list($options, $unrecognized) = cli_get_params(
    [
        'help'      => false,
        'timeframe' => 90,
        'limit'     => 100,
        'offset'    => 0,
        'format'    => 'json',
        'pluginfilter' => ''
    ],
    [
        'h' => 'help',
    ]
);

if ($options['help'] || !CLI_SCRIPT) {
    echo "Plugin Usage Reporter (CLI)
Fetch Moodle plugin/module usage data as admin, directly via command line.

Options:
--timeframe=N       Number of days to look back (default: 90)
--limit=N           Limit number of records (default: 100)
--offset=N          Offset for pagination (default: 0)
--format=TYPE       Output format: json, csv, txt, xml (default: json)
--pluginfilter=PLUG Plugin shortname filter, e.g. 'mod_assign' (optional)
--help, -h          Show this help

Example:
php admin/cli/plugin_usage_report.php --timeframe=30 --format=csv > report.csv

\n";
    exit(0);
}

// Admin capability check
\core\session\manager::login_as($USER, context_system::instance());

if (!is_siteadmin()) {
    cli_error("You must be a site admin to run this script.");
}

$timeframe = (int)$options['timeframe'];
$limit     = (int)$options['limit'];
$offset    = (int)$options['offset'];
$format    = strtolower(trim($options['format']));

// Instantiate RawDataFetcher (use Moodle's global $DB)
$fetcher = new RawDataFetcher($DB);

$fetcher->setPagination($limit, $offset);

try {
    $records = $fetcher->fetchData($timeframe);

    // Optionally filter by pluginfilter
    if (!empty($options['pluginfilter']) && is_array($records)) {
        $pluginfilter = trim($options['pluginfilter']);
        $records = array_filter($records, function($row) use ($pluginfilter) {
            return isset($row->modulename) && $row->modulename === $pluginfilter;
        });
    }
    // Attaction Transform Data dose not exist in the RawDataFetcher class anymore.
    //@ToDO Fix this°!
    //$output = $fetcher->transformData(is_array($records) ? array_values($records) : [], $format);

    echo $output . PHP_EOL;

} catch (Exception $e) {
    cli_error("Error: " . $e->getMessage());
}


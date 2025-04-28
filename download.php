<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/pluginusagereporter:viewreports', $context);

$id = required_param('id', PARAM_INT);
$format = required_param('format', PARAM_ALPHA);

global $DB;

if (!$report = $DB->get_record('pluginusagereporter_reports', ['id' => $id])) {
    throw new \moodle_exception('errorreportnotfound', 'local_pluginusagereporter');
}

$data = json_decode($report->reportjson, true);

if (empty($data)) {
    throw new \moodle_exception('errornodata', 'local_pluginusagereporter');
}

// Set filename
$filename = "plugin_usage_report_{$id}." . strtolower($format);

// Output headers
switch ($format) {
    case 'csv':
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename={$filename}");
        output_csv($data);
        break;
    case 'txt':
        header('Content-Type: text/plain');
        header("Content-Disposition: attachment; filename={$filename}");
        output_txt($data);
        break;
    case 'html':
        header('Content-Type: text/html');
        header("Content-Disposition: attachment; filename={$filename}");
        output_html($data);
        break;
    default:
        throw new \moodle_exception('errorinvalidformat', 'local_pluginusagereporter');
}

exit;

/**
 * Outputs the given data as a CSV file.
 *
 * @param array $data The data to output in CSV format. Each element should be an associative array representing a row.
 *                    The keys of the first element will be used as the CSV header.
 * 
 * @return void
 */

function output_csv(array $data): void {
    $output = fopen('php://output', 'w');
    if (isset($data[0])) {
        fputcsv($output, array_keys($data[0]));
    }
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

/**
 * Outputs the given data as a tab-separated text file.
 *
 * @param array $data The data to output in tab-separated format. Each element should be an associative array representing a row.
 *                    The first element will be used as the header.
 *
 * @return void
 */
function output_txt(array $data): void {
    foreach ($data as $row) {
        echo implode("\t", $row) . PHP_EOL;
    }
}

/**
 * Outputs the given data as an HTML table.
 *
 * @param array $data The data to output in HTML table format. Each element should be an associative array representing a row.
 *                    The keys of the first element will be used as the table headers.
 *
 * @return void
 */

function output_html(array $data): void {
    echo "<table border='1'>";
    if (isset($data[0])) {
        echo "<tr>";
        foreach (array_keys($data[0]) as $heading) {
            echo "<th>" . s($heading) . "</th>";
        }
        echo "</tr>";
    }
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . s($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

<?php
namespace local_pluginusagereporter\generator;

use local_pluginusagereporter\generator\GeneratorInterface;
use coding_exception;
/**
 * Generator for CSV reports.
 */
class CsvReportGenerator implements GeneratorInterface {

    public function generate(array $data): string {
        if (empty($data)) {
            return "No data available\n";
        }

        $fh = fopen('php://temp', 'r+');
        if ($fh === false) {
            throw new \coding_exception('Failed to open memory for CSV generation.');
        }

        fputcsv($fh, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($fh, array_map('strval', $row));
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return (string)$csv;
    }
}

<?php 
namespace local_pluginusagereporter\generator;

defined('MOODLE_INTERNAL') || die();

use local_pluginusagereporter\generator\GeneratorInterface;
/**
 * Generator for TXT reports (tab-separated values).
 */
class TextReportGenerator implements GeneratorInterface {

    public function generate(array $data): string {
        if (empty($data)) {
            return "No data available.\n";
        }

        $lines = [];
        $headers = array_keys($data[0]);
        $lines[] = implode("\t", $headers);

        foreach ($data as $row) {
            $lines[] = implode("\t", array_map('strval', $row));
        }

        return implode("\n", $lines);
    }
}

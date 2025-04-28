<?php
namespace local_pluginusagereporter\generator;

defined('MOODLE_INTERNAL') || die();

use local_pluginusagereporter\generator\GeneratorInterface;
/**
 * Generator for JSON reports (prepared, usable).
 */
class JsonReportGenerator implements GeneratorInterface {

    /**
     * Generate a JSON report of plugin usage.
     *
     * @param array $data The plugin usage data to generate a report for.
     * @return string The generated report as a JSON string.
     */
    public function generate(array $data): string {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '{}';
    }
}

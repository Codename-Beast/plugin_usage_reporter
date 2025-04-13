<?php 
namespace local_pluginusagereporter\reportgenerator;

defined('MOODLE_INTERNAL') || die();

class TextReportGenerator implements ReportGeneratorInterface {
    /**
     * Generate a plaintext report from the given data.
     * @param array $data List of objects with the following properties:
     *                     - modulename: string
     *                     - coursename: string
     *                     - usagecount: int
     *                     - lastused: int (unix timestamp)
     *                     - user_count: int
     *                     - roles: string
     * @return string The generated report.
     */
    public function generate(array $data): string {
        $text = get_string('pluginusagereport', 'local_pluginusagereporter') . "\n\n";
        foreach ($data as $entry) {
            $text .= sprintf(
                "%-20s %-30s %-15d %-25s %-15d %s\n",
                $entry->modulename,
                $entry->coursename,
                $entry->usagecount,
                userdate($entry->lastused),
                $entry->user_count,
                $entry->roles
            );
        }
        return $text;
    }
}
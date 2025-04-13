<?php
namespace local_pluginusagereporter\reportgenerator;

defined('MOODLE_INTERNAL') || die();

use flexible_table;

class HtmlReportGenerator implements ReportGeneratorInterface {
/**
 * Generates an HTML report table from the given plugin usage data.
 *
 * @param array $data List of objects with the following properties:
 *                     - modulename: string
 *                     - coursename: string
 *                     - usagecount: int
 *                     - lastused: int (unix timestamp)
 *                     - user_count: int
 *                     - roles: string
 * @return string The generated HTML report as a string.
 */

    public function generate(array $data): string {
        global $OUTPUT;

        $table = new flexible_table('pluginusagereporter_report_table');
        $table->define_columns(['modulename', 'coursename', 'usagecount', 'lastusage', 'usercount', 'userroles']);
        $table->define_headers([
            get_string('modulename', 'local_pluginusagereporter'),
            get_string('coursename', 'local_pluginusagereporter'),
            get_string('usagecount', 'local_pluginusagereporter'),
            get_string('lastusage', 'local_pluginusagereporter'),
            get_string('usercount', 'local_pluginusagereporter'),
            get_string('userroles', 'local_pluginusagereporter')
        ]);
        
        ob_start();
        foreach ($data as $entry) {
            $table->add_data([
                $entry->modulename,
                $entry->coursename,
                $entry->usagecount,
                userdate($entry->lastused),
                $entry->user_count,
                $entry->roles
            ]);
        }
        $table->finish_output();
        return $OUTPUT->heading(get_string('pluginusagereport', 'local_pluginusagereporter'), 2) . ob_get_clean();
    }
}
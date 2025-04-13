<?php 
namespace local_pluginusagereporter\output;

use renderable;
use templatable;
use stdClass;

class dashboard implements renderable, templatable {

    // This function exports data for use in a template by fetching plugin usage reports from the database.
    public function export_for_template(\renderer_base $output) {
        global $DB;
        
        // Retrieve reports ordered by timestamp in descending order
        $reports = $DB->get_records('local_pluginusagereporter_reports', null, 'timestamp DESC');
        
        // Transform each report record into an array with desired fields
        return [
            'reports' => array_values(array_map(function($report) {
                return [
                    'id' => $report->id, // Report ID
                    'date' => userdate($report->timestamp), // Formatted timestamp
                    'timeframe' => $report->timeframe_days . ' Tage', // Timeframe in days
                    'entries' => count(json_decode($report->report_data)) // Number of entries in report data
                ];
            }, $reports))
        ];
    }
}

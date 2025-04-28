<?php
namespace local_pluginusagereporter\output;

use renderable;
use templatable;
use renderer_base;
use moodle_url;
use context_system;

defined('MOODLE_INTERNAL') || die();

class dashboard_renderer implements renderable, templatable {

    /**
     * Export the data needed for the dashboard template.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        global $DB;

        $records = $DB->get_records('pluginusagereporter_reports', null, 'created_at DESC');

        $data = [];
        foreach ($records as $record) {
            $user = \core_user::get_user($record->userid);
            $data['reports'][] = [
                'id' => $record->id,
                'username' => $user ? fullname($user) : get_string('systemuser', 'local_pluginusagereporter'),
                'created' => userdate($record->created_at),
                'downloadurlcsv' => new moodle_url('/local/pluginusagereporter/download.php', ['id' => $record->id, 'format' => 'csv']),
                'downloadurltxt' => new moodle_url('/local/pluginusagereporter/download.php', ['id' => $record->id, 'format' => 'txt']),
                'downloadurlhtml' => new moodle_url('/local/pluginusagereporter/download.php', ['id' => $record->id, 'format' => 'html']),
                'deleteurl' => new moodle_url('/local/pluginusagereporter/delete.php', ['id' => $record->id]),
            ];
        }

        return $data;
    }

    /**
     * Renders the dashboard for the plugin usage reporter.
     *
     * Utilizes the Moodle page renderer to render the dashboard using the
     * 'local_pluginusagereporter/dashboard' template.
     *
     * @return string The rendered HTML output of the dashboard.
     */

    public function render_dashboard(): string {
        global $PAGE;
        return $PAGE->get_renderer('local_pluginusagereporter')->render_from_template('local_pluginusagereporter/dashboard', $this);
    }
}

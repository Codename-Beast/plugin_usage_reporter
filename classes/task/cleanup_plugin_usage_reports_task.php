<?php
namespace local_pluginusagereporter\task;

defined('MOODLE_INTERNAL') || die();

class delete_old_reports_task extends \core\task\scheduled_task {

    public function get_name(): string {
        return get_string('task_delete_old_reports', 'local_pluginusagereporter');
    }

    public function execute(): void {
        global $DB;

        $days = (int) get_config('local_pluginusagereporter', 'autodelete_days');
        if ($days <= 0) {
            return;
        }

        $threshold = time() - ($days * DAYSECS);
        $oldreports = $DB->get_records_select('pluginusagereporter_reports', 'created_at < ?', [$threshold]);

        foreach ($oldreports as $report) {
            $DB->delete_records('pluginusagereporter_reports', ['id' => $report->id]);

            \local_pluginusagereporter\event\report_deleted::create([
                'objectid' => $report->id,
                'context' => \context_system::instance(),
            ])->trigger();
        }
    }
}

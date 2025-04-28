<?php
namespace local_pluginusagereporter\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event for report deletion in Plugin Usage Reporter.
 *
 * @package   local_pluginusagereporter
 */
class report_deleted extends \core\event\base {

    /**
     * Set basic properties for the event.
     *
     * @return void
     */
    protected function init(): void {
        $this->data['crud'] = 'd'; // delete
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    public static function get_name(): string {
        return get_string('eventreportdeleted', 'local_pluginusagereporter');
    }

    /**
     * Returns a description of the report deleted event.
     *
     * If the report was deleted by the system, it indicates a CLI action.
     * Otherwise, it specifies the user ID and report ID involved in the deletion.
     *
     * @return string Description of the event.
     */
    public function get_description(): string {
        if (!empty($this->other['source']) && $this->other['source'] === 'system') {
            return "A plugin usage report was deleted by the system (CLI action).";
        }
        return "The user with id '{$this->userid}' deleted a plugin usage report (ID {$this->objectid}).";
    }

    /**
     * Returns the URL for the report view page that the user can visit
     * to view the report deleted by this event.
     * 
     * @return \moodle_url
     */
    public function get_url(): \moodle_url {
        return new \moodle_url('/local/pluginusagereporter/dashboard.php');
    }
}

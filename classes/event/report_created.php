<?php
namespace local_pluginusagereporter\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event for report creation in Plugin Usage Reporter.
 *
 * @package   local_pluginusagereporter
 * @copyright 2025
 */
class report_created extends \core\event\base {

    /**
     * Set basic properties for the event.
     *
     * @return void
     */
    protected function init(): void {
        $this->data['crud'] = 'c'; // create
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Get the name of the event.
     *
     * @return string
     */
    public static function get_name(): string {
        return get_string('eventreportcreated', 'local_pluginusagereporter');
    }

    /**
     * Returns a description of the report created event.
     *
     * If the report was created by the system, it indicates a CLI action.
     * Otherwise, it specifies the user ID and report ID involved in the creation.
     *
     * @return string Description of the event.
     */
    public function get_description(): string {
        if (!empty($this->other['source']) && $this->other['source'] === 'system') {
            return "A plugin usage report was created by the system (CLI action).";
        }
        return "The user with id '{$this->userid}' created a plugin usage report (ID {$this->objectid}).";
    }

    /**
     * Returns the URL for the report view page that the user can visit
     * to view the report created by this event.
     *
     * @return \moodle_url
     */
    public function get_url(): \moodle_url {
        return new \moodle_url('/local/pluginusagereporter/dashboard.php', ['id' => $this->objectid]);
    }
}

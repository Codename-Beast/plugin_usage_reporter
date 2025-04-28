<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class tasks_settings_form extends \moodleform {
    /**
     * Defines the form fields for tasks and cron settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Enable scheduled task.
        $mform->addElement('advcheckbox', 'enable_scheduled_task', get_string('enable_scheduled_task', 'local_pluginusagereporter'));
        $mform->setDefault('enable_scheduled_task', get_config('local_pluginusagereporter', 'enable_scheduled_task') ?? 1);

        // Retry delay (in seconds) after a failed attempt.
        $mform->addElement('text', 'retry_delay', get_string('retry_delay', 'local_pluginusagereporter'));
        $mform->setType('retry_delay', PARAM_INT);
        $mform->setDefault('retry_delay', get_config('local_pluginusagereporter', 'retry_delay') ?? 300);

        // Auto-delete reports after X days.
        $mform->addElement('text', 'autodelete_days', get_string('autodelete_days', 'local_pluginusagereporter'));
        $mform->setType('autodelete_days', PARAM_INT);
        $mform->setDefault('autodelete_days', get_config('local_pluginusagereporter', 'autodelete_days') ?? 180);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

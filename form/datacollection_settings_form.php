<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class datacollection_settings_form extends \moodleform {
    /**
     * Defines the form fields for data collection settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Enable event logging.
        $mform->addElement('advcheckbox', 'enablelogging', get_string('enablelogging', 'local_pluginusagereporter'));
        $mform->setDefault('enablelogging', get_config('local_pluginusagereporter', 'enablelogging') ?? 1);

        // Auto-delete old saved reports after X days.
        $mform->addElement('text', 'autodelete', get_string('autodelete', 'local_pluginusagereporter'));
        $mform->setType('autodelete', PARAM_INT);
        $mform->setDefault('autodelete', get_config('local_pluginusagereporter', 'autodelete') ?? 180);

        // CLI Logging (Sysadmin CLI Actions).
        $mform->addElement('advcheckbox', 'cli_logging', get_string('cli_logging', 'local_pluginusagereporter'));
        $mform->setDefault('cli_logging', get_config('local_pluginusagereporter', 'cli_logging') ?? 1);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

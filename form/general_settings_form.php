<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class general_settings_form extends \moodleform {
    /**
     * Defines the form fields for general settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Default timeframe (number of days to look back).
        $mform->addElement('text', 'defaulttimeframe', get_string('defaulttimeframe', 'local_pluginusagereporter'));
        $mform->setType('defaulttimeframe', PARAM_INT);
        $mform->setDefault('defaulttimeframe', get_config('local_pluginusagereporter', 'defaulttimeframe') ?? 90);

        // Include hidden courses.
        $mform->addElement('advcheckbox', 'includehidden', get_string('includehidden', 'local_pluginusagereporter'));
        $mform->setDefault('includehidden', get_config('local_pluginusagereporter', 'includehidden') ?? 0);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

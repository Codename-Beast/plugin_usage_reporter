<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class apisettings_form extends \moodleform {
    /**
     * Defines the form fields for API settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Rate limit (requests).
        $mform->addElement('text', 'rate_limit', get_string('rate_limit', 'local_pluginusagereporter'));
        $mform->setType('rate_limit', PARAM_INT);
        $mform->setDefault('rate_limit', get_config('local_pluginusagereporter', 'rate_limit') ?? 100);

        // Rate limit window (seconds).
        $mform->addElement('text', 'rate_limit_window', get_string('rate_limit_window', 'local_pluginusagereporter'));
        $mform->setType('rate_limit_window', PARAM_INT);
        $mform->setDefault('rate_limit_window', get_config('local_pluginusagereporter', 'rate_limit_window') ?? 3600);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

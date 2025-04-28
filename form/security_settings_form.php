<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class security_settings_form extends \moodleform {
    /**
     * Defines the form fields for security settings.
     */
    public function definition() {
        $mform = $this->_form;

        // IP Whitelist.
        $mform->addElement('textarea', 'ip_whitelist', get_string('ip_whitelist', 'local_pluginusagereporter'));
        $mform->setType('ip_whitelist', PARAM_TEXT);
        $mform->setDefault('ip_whitelist', get_config('local_pluginusagereporter', 'ip_whitelist') ?? '');

        // Admin override access.
        $mform->addElement('advcheckbox', 'admin_override', get_string('admin_override', 'local_pluginusagereporter'));
        $mform->setDefault('admin_override', get_config('local_pluginusagereporter', 'admin_override') ?? 1);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

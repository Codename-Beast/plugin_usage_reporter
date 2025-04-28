<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class caching_settings_form extends \moodleform {
    /**
     * Defines the form fields for caching settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Enable caching for fetched data.
        $mform->addElement('advcheckbox', 'enable_caching', get_string('enable_caching', 'local_pluginusagereporter'));
        $mform->setDefault('enable_caching', get_config('local_pluginusagereporter', 'enable_caching') ?? 1);

        // Cache TTL (Time-To-Live) in seconds.
        $mform->addElement('text', 'cache_ttl', get_string('cache_ttl', 'local_pluginusagereporter'));
        $mform->setType('cache_ttl', PARAM_INT);
        $mform->setDefault('cache_ttl', get_config('local_pluginusagereporter', 'cache_ttl') ?? 3600);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

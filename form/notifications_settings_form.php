<?php
namespace local_pluginusagereporter\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class notifications_settings_form extends \moodleform {
    /**
     * Defines the form fields for notifications settings.
     */
    public function definition() {
        $mform = $this->_form;

        // Enable email notifications.
        $mform->addElement('advcheckbox', 'enable_email_notifications', get_string('enable_email_notifications', 'local_pluginusagereporter'));
        $mform->setDefault('enable_email_notifications', get_config('local_pluginusagereporter', 'enable_email_notifications') ?? 0);

        // Notification email address.
        $mform->addElement('text', 'notification_email', get_string('notification_email', 'local_pluginusagereporter'));
        $mform->setType('notification_email', PARAM_EMAIL);
        $mform->setDefault('notification_email', get_config('local_pluginusagereporter', 'notification_email') ?? '');

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

<?php
/**
 * [Initial Notification Class]
 *
 * Notifier class for Plugin Usage Reporter.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter;

defined('MOODLE_INTERNAL') || die();

class notifier
{
    /**
     * [Since v1.0.0-10 E] Send an email notification if enabled.
     *
     * @param string $subject Email subject.
     * @param string $message Email message.
     * @return void
     */
    public static function send(string $subject, string $message): void
    {
        if (!get_config('local_pluginusagereporter', 'enable_notifications')) {
            return;
        }

        $recipient = get_config('local_pluginusagereporter', 'email');
        if (empty($recipient)) {
            return;
        }

        $admin = \core_user::get_support_user();
        email_to_user($admin, $admin, $subject, $message);
    }
}

<?php
/**
 * v1.1.1-10 I 2025-04-13 [Initial Event Listener]
 *
 * Event listener for Plugin Usage Reporter.
 * Listens to course/view and module activity events to trigger reports.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\event;

defined('MOODLE_INTERNAL') || die();

use local_pluginusagereporter\datafetcher\RawDataFetcher;
use local_pluginusagereporter\external\api_handler;
use local_pluginusagereporter\logger;
use local_pluginusagereporter\ErrorHandler;

class PluginUsageEvent
{
    /**
     * [Since v1.1.1-10 I] Event handler for course viewed and module viewed events.
     *
     * @param \core\event\base $event
     * @return void
     */
    public static function trigger_report(\core\event\base $event): void
    {
        try {
            // Simple debug log
            logger::add('success', 'Event triggered: ' . $event->eventname, [
                'objectid' => $event->objectid,
                'contextid' => $event->contextid
            ]);

            // Optional: Check if API trigger via event is enabled
            if (!get_config('local_pluginusagereporter', 'enable_event_api')) {
                return;
            }

            $fetcher = new RawDataFetcher($GLOBALS['DB']);
            $timeframe = (int) get_config('local_pluginusagereporter', 'timeframe');
            $data = $fetcher->fetch_data($timeframe);

            if (get_config('local_pluginusagereporter', 'enable_external_api')) {
                $api = new api_handler();
                $success = $api->send_report($data);

                if ($success) {
                    logger::add('success', 'API report sent via event trigger.', ['event' => $event->eventname]);
                } else {
                    logger::add('error', 'API report failed via event trigger.', ['event' => $event->eventname]);
                }
            } else {
                logger::add('success', 'Report generated locally via event trigger.', ['event' => $event->eventname]);
            }

        } catch (\Throwable $e) {
            ErrorHandler::handle($e);
        }
    }
}

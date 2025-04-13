<?php
/**
 * v1.1.1-10 I 2025-04-13 [Event observers for live trigger]
 *
 * @package    local_pluginusagereporter
 */

defined('MOODLE_INTERNAL') || die();

return [
    [
        'eventname' => '\core\event\course_viewed',
        'callback' => '\local_pluginusagereporter\event\PluginUsageEvent::trigger_report',
        'includefile' => '/local/pluginusagereporter/classes/event/PluginUsageEvent.php',
        'priority' => 9999,
        'internal' => false,
    ],
    [
        'eventname' => '\core\event\course_module_viewed',
        'callback' => '\local_pluginusagereporter\event\PluginUsageEvent::trigger_report',
        'includefile' => '/local/pluginusagereporter/classes/event/PluginUsageEvent.php',
        'priority' => 9999,
        'internal' => false,
    ]
];

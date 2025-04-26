<?php
// This file is part of Moodle - http://moodle.org/
// 
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Privacy Subsystem implementation for local_pluginusagereporter.
 *
 * @package    local_pluginusagereporter
 * @copyright  2025 Codename-Beast
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_pluginusagereporter\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\metadata\collection;

/**
 * Privacy provider for local_pluginusagereporter.
 */
final class provider implements metadata_provider {

    /**
     * Returns metadata.
     *
     * @param collection $collection The metadata collection to fill.
     * @return collection The updated collection of metadata items.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'local_pluginusagereporter_log',
            [
                'level' => 'privacy:metadata:log:level',
                'message' => 'privacy:metadata:log:message',
                'timecreated' => 'privacy:metadata:log:timecreated'
            ],
            'privacy:metadata:log'
        );

        return $collection;
    }
}

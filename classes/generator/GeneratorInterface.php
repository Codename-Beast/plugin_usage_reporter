<?php 
// This file is part of Moodle - http://moodle.org/

namespace local_pluginusagereporter\generator;

defined('MOODLE_INTERNAL') || die();
/**
 * Interface for report generators.
 * This interface is used by the plugin to generate HTML or text reports of plugin usage.
 * Implementations of this interface should provide a generate method that takes an array of
 * plugin usage data and returns a string containing the report in the desired format.
 * The array of data should contain the following properties for each plugin:
 * - modulename: string
 * - coursename: string
 * - usagecount: int
 * - lastused: int (unix timestamp)
 * - user_count: int
 * - roles: string
 */
interface GeneratorInterface {
    public function generate(array $data): string;
}
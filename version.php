<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * This plugin provides reports on plugin usage for Moodle .
 *
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT 
 */

 defined('MOODLE_INTERNAL') || die();

 $plugin->component = 'local_pluginusagereporter';
 $plugin->version = 2025042800; // Entwicklungs-Release für 28. April 2025 (v2.3 dev)
 $plugin->release = '2.3.0 (Build: 2025042800)';
 $plugin->maturity = MATURITY_STABLE;
 $plugin->requires = 2023100900; // Moodle 4.5+
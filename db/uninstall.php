<?php 
/**
 * Removes all records from the local_pluginusagereporter_reports table
 * when the plugin is uninstalled.
 *
 * @return bool true if uninstallation was successful
 */
function xmldb_local_pluginusagereporter_uninstall() {
    global $DB;
    $DB->delete_records('local_pluginusagereporter_reports');
    return true;
}
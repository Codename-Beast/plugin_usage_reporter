<?php 
 /* 
 Step 5: Create the  upgrade.php  file
 The  upgrade.php  file is used to upgrade the plugin. The  upgrade()  function is called when the plugin is upgraded. The  upgrade()  function is passed the old version of the plugin. The  upgrade()  function should return  true  if the upgrade was successful. 
 The  upgrade()  function checks if the old version is less than  2024100800 . If it is, the function creates the  local_pluginusagereporter_reports  table. The  upgrade_plugin_savepoint()  function is then called to save the upgrade point. 
 The  upgrade_plugin_savepoint()  function is used to save the upgrade point. The  upgrade_plugin_savepoint()  function is passed the following parameters: 
 
 $pluginname : The name of the plugin. 
 $version : The version of the plugin. 
 $component : The component of the plugin. 
 $subplugin : The subplugin of the plugin. 
 
 The  upgrade_plugin_savepoint()  function saves the upgrade point in the  mdl_config  table. 
 Step 6: Create the  uninstall.php  file 
 The  uninstall.php  file is used to uninstall the plugin. The  uninstall()  function is called when the plugin is uninstalled. The  uninstall()  function should return  true  if the uninstall was successful. 
 The  uninstall.php  file should be placed in the  db  directory.
 */
function xmldb_local_pluginusagereporter_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2024100800) {
        $table = new xmldb_table('local_pluginusagereporter_reports');
        if (!$dbman->table_exists($table)) {
            $dbman->install_one_table_from_xmldb_file(
                __DIR__.'/install.xml',
                'local_pluginusagereporter_reports'
            );
        }
        upgrade_plugin_savepoint(true, 2024100800, 'local', 'pluginusagereporter');
    }
    
    return true;
}
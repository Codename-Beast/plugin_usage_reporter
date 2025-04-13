<?php
/**
 * This plugin provides reports on plugin usage for Moodle.
 * English language file for local_pluginusagereporter
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT 
 */

defined('MOODLE_INTERNAL') || die();

// Plugin and Task Names
$string['pluginname'] = 'Plugin Usage Reporter';
$string['taskname'] = 'Generate Plugin Usage Report Task';

// Settings
$string['pluginsettings'] = 'Plugin Settings';
$string['emailsetting'] = 'Email Address';
$string['emailsetting_desc'] = 'Email address where the usage report should be sent';
$string['timeframesetting'] = 'Timeframe (Days)';
$string['timeframesetting_desc'] = 'Number of days in the past for which usage data should be collected';
$string['frequencysetting'] = 'Report Frequency';
$string['frequencysetting_desc'] = 'How often the usage report should be generated';
$string['configdaterange'] = 'Date Range for Reporting';
$string['configdaterange_desc'] = 'Select the time range for which reports should be generated: 3, 6, or 12 months.';
$string['reporttimeframe'] = 'Report timeframe';
$string['reporttimeframe_desc'] = 'The number of days to include in the plugin usage report. Must be between 1 and 365.';

// Report Periods
$string['month3'] = 'Last 3 months';
$string['month6'] = 'Last 6 months';
$string['month12'] = 'Last 12 months';

// Frequency Options
$string['daily'] = 'Daily';
$string['weekly'] = 'Weekly';
$string['monthly'] = 'Monthly';

// Report Columns
$string['modulename'] = 'Module Name';
$string['coursename'] = 'Course Name';
$string['courseid'] = 'Course ID';
$string['usagecount'] = 'Usage Count';
$string['timestamp'] = 'Timestamp';

// Status and Error Messages
$string['invalidemail'] = 'Invalid email address configured in plugin settings. No email will be sent.';
$string['validemail'] = 'Valid email address';
$string['checkingusage'] = 'Checking plugin usage for courses accessed in the configured period';
$string['foundusage'] = 'Found plugin usages';
$string['pluginusagereport'] = 'Plugin Usage Report';
$string['reportgenerated'] = 'Report generated';
$string['sendingreport'] = 'Sending report to email';
$string['emailsent'] = 'Report email sent successfully';
$string['materializedview_unsupported'] = 'Materialized Views not supported in {$a}';
$string['materializedview_missing'] = 'Materialized View not found in database';
$string['materializedview_error'] = 'Materialized View query error';
$string['fallback_raw_query'] = 'Falling back to raw data query';

// Cronjob Messages
$string['cronjobmessage'] = 'Plugin Usage Reporter cronjob is running';
$string['cronjobnotfound'] = 'Scheduled task {$a} was not found';
$string['cronjobregistered'] = 'Scheduled task {$a} was successfully registered';
$string['cronjobnotfoundexception'] = 'The scheduled task could not be registered';
$string['adminusernotfound'] = 'Admin user could not be found.';
//Admin Dashboard 
$string['dashboardtitle'] = 'Plugin Usage Dashboard';
$string['action_details'] = 'View Details';
$string['report_entries'] = 'Entries';
$string['id'] = 'ID';
$string['date'] = 'Date';
$string['timeframe'] = 'Timeframe';
$string['entries'] = 'Entries';
$string['actions'] = 'Actions';
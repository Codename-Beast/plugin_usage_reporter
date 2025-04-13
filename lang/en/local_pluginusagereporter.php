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
// [Since v1.1.1-10 A]: Manual API trigger button label
$string['manualtrigger'] = 'Generate and Send Report Manually';
$string['manualtrigger_desc'] = 'Click to manually generate and send the plugin usage report.';
//$string['manualtrigger_success'] = 'Manual report generation and sending successful.';
$string['invalidapikey'] = 'Invalid API key provided.';
$string['apidescription'] = 'Provides plugin usage data for external monitoring systems.';
$string['instances'] = 'Moodle Instances Configuration';
$string['instances_desc'] = 'Define multiple Moodle instances in JSON format for cross-instance reporting.';
// [Since v1.1.1-10 E]: Notification settings
$string['enable_notifications'] = 'Enable Email Notifications';
$string['enable_notifications_desc'] = 'Send email notifications for report events and API triggers.';
// [Since v1.1.1-10 G]: Caching settings
$string['enable_caching'] = 'Enable Caching';
$string['enable_caching_desc'] = 'Enable or disable caching for plugin usage reports.';
$string['cache_ttl'] = 'Cache Lifetime (seconds)';
$string['cache_ttl_desc'] = 'Time to live for cache entries in seconds.';
// [Since v1.1.1-10 I]: Event trigger settings
$string['enable_event_api'] = 'Enable Event-based API Trigger';
$string['enable_event_api_desc'] = 'Automatically trigger reports when specific events happen in Moodle.';

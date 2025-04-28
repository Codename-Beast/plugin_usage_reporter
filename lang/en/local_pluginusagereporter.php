<?php
// This file is part of local_pluginusagereporter – Moodle plugin for plugin usage reporting.
// Licensed under the GPL v3 or later – see https://www.gnu.org/copyleft/gpl.html

defined('MOODLE_INTERNAL') || die();

// General
$string['pluginname'] = 'Plugin Usage Reporter';
$string['privacy:metadata'] = 'The Plugin Usage Reporter plugin does not store any personal data.';

// Dashboard / Overview
$string['dashboardtitle'] = 'Plugin Usage Dashboard';
$string['action_details'] = 'View Details';
$string['report_entries'] = 'Entries';
$string['id'] = 'ID';
$string['date'] = 'Date';
$string['timeframe'] = 'Timeframe';
$string['entries'] = 'Entries';
$string['actions'] = 'Actions';
$string['user'] = 'User';
$string['createdby'] = 'Created by';
$string['createdat'] = 'Created at';
$string['deleted_success'] = 'Report successfully deleted.';
$string['delete_failed'] = 'Failed to delete the report.';
$string['no_reports_found'] = 'No saved reports found.';

// API Manual Trigger
$string['manualtrigger'] = 'Generate and Send Report Manually';
$string['manualtrigger_desc'] = 'Click here to manually generate and send the plugin usage report.';

// Notifications
$string['enable_notifications'] = 'Enable Email Notifications';
$string['enable_notifications_desc'] = 'Send email notifications for report events and API triggers.';
$string['notification_email'] = 'Notification email address';

// Caching
$string['enable_caching'] = 'Enable Caching';
$string['enable_caching_desc'] = 'Enable or disable caching for plugin usage reports.';
$string['cache_ttl'] = 'Cache Lifetime (seconds)';
$string['cache_ttl_desc'] = 'Time to live for cache entries in seconds.';

// Event API Trigger
$string['enable_event_api'] = 'Enable Event-based API Trigger';
$string['enable_event_api_desc'] = 'Automatically trigger reports when specific Moodle events occur.';

// Tasks
$string['enable_scheduled_task'] = 'Enable scheduled task';
$string['retry_delay'] = 'Retry delay (seconds)';
$string['autodelete_days'] = 'Auto-delete reports after (days)';

// Security
$string['ip_whitelist'] = 'IP whitelist (allowed IPs)';
$string['admin_override'] = 'Allow admin override (ignore IP restrictions)';

// API Settings
$string['rate_limit'] = 'API rate limit (requests)';
$string['rate_limit_window'] = 'API rate limit window (seconds)';

// Data Collection
$string['enablelogging'] = 'Enable event logging';
$string['autodelete'] = 'Auto-delete saved reports after (days)';
$string['cli_logging'] = 'Enable CLI logging for system actions';

// General Settings
$string['defaulttimeframe'] = 'Default timeframe (days)';
$string['includehidden'] = 'Include hidden courses';

// Tabs
$string['generalsettings'] = 'General Settings';
$string['cachingsettings'] = 'Caching Settings';
$string['cachingsettings_desc'] = 'Configure caching behavior for report data';
$string['securitysettings'] = 'Security Settings';
$string['securitysettings_desc'] = 'Security and access control configuration';
$string['datacollectionsettings'] = 'Data Collection Settings';
$string['tasksettings'] = 'Tasks and Automation Settings';
$string['apisettings'] = 'API Settings';
$string['notificationsettings'] = 'Notification Settings';

// Error messages
$string['error_invalidtimeframe'] = 'Invalid timeframe provided.';
$string['error_db'] = 'A database error occurred.';
$string['error_db_unsupported'] = 'Your database type is not supported for this operation.';
$string['error_db_requires_mysql8'] = 'This operation requires MySQL 8.0 or higher.';
$string['error_invalidformat'] = 'Invalid export format specified.';

// CLI and Admin errors
$string['adminusernotfound'] = 'Admin user could not be found.';
$string['notallowed'] = 'You are not allowed to access this functionality.';
$string['clierror'] = 'CLI Error: You must be a site administrator to run this script.';

<?php
/**
 * v1.1.1-10 I 2025-04-18 [Caching Setting Respected]
 *
 * Dashboard for Plugin Usage Reporter.
 *
 * Features:
 * - Manual report trigger
 * - Filterable and paginated log history
 * - Conditional caching based on plugin settings
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

require_once(__DIR__ . '/../../config.php');
require_login();

use local_pluginusagereporter\datafetcher\RawDataFetcher;
use local_pluginusagereporter\external\api_handler;
use local_pluginusagereporter\ErrorHandler;
use local_pluginusagereporter\logger;
use moodle_url;
use html_writer;

// [Since v1] Page setup
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/pluginusagereporter/dashboard.php'));
$PAGE->set_title(get_string('dashboardtitle', 'local_pluginusagereporter'));
$PAGE->set_heading(get_string('dashboardtitle', 'local_pluginusagereporter'));

// Get filter params
$logtype = optional_param('logtype', '', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 10;

// [Since v1] Output start
echo $OUTPUT->header();

// [Since v1.1.1-10 A] Manual API trigger
if (optional_param('sendreport', false, PARAM_BOOL)) {
    try {
        $fetcher = new RawDataFetcher($GLOBALS['DB']);
        $timeframe = (int) get_config('local_pluginusagereporter', 'timeframe');
        $data = $fetcher->fetchData($timeframe);

        // [Since v1.1.1-10 I] Conditional caching based on plugin settings
        if (get_config('local_pluginusagereporter', 'enable_caching')) {
            $fetcher->cacheData('manual_dashboard_trigger', $data, 3600);
        }

        if (get_config('local_pluginusagereporter', 'enable_external_api')) {
            $api = new api_handler();
            $success = $api->send_report($data);

            if ($success) {
                \core\notification::success('Manual API report successfully sent.');
                logger::add('success', 'Manual API report successfully sent.', ['method' => 'dashboard']);
            } else {
                \core\notification::error('Manual API report failed. Check logs for details.');
                logger::add('error', 'Manual API report failed.', ['method' => 'dashboard']);
            }
        } else {
            \core\notification::info('External API export is disabled. Report generated and cached locally.');
            logger::add('success', 'Report generated and cached locally (API disabled).', ['method' => 'dashboard']);
        }
    } catch (\Throwable $e) {
        (new ErrorHandler())->handle($e);
        \core\notification::error('Error during manual report generation: ' . $e->getMessage());
        logger::add('error', 'Exception during manual report generation: ' . $e->getMessage(), ['method' => 'dashboard']);
    }
}

// [Since v1.1.1-10 A] Manual trigger button
$url = new moodle_url('/local/pluginusagereporter/dashboard.php', ['sendreport' => 1]);
echo $OUTPUT->single_button($url, get_string('manualtrigger', 'local_pluginusagereporter'));

// [Since v1.1.1-10 H] Filter form
echo html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url]);
echo html_writer::start_tag('div');
echo html_writer::label('Filter by log type:', 'logtype');
echo html_writer::select(
    ['' => 'All', 'success' => 'Success', 'error' => 'Error'],
    'logtype',
    $logtype,
    false
);
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => 'Filter']);
echo html_writer::end_tag('div');
echo html_writer::end_tag('form');

// [Since v1.1.1-10 H] Display latest report logs in dashboard with pagination
echo $OUTPUT->heading('Report History', 3);

list($logs, $totalcount) = logger::get_latest_paginated($logtype, $perpage, $page * $perpage);

if (!empty($logs)) {
    echo html_writer::start_tag('table', ['class' => 'generaltable', 'style' => 'width: 100%;']);
    echo html_writer::start_tag('thead');
    echo html_writer::start_tag('tr');
    echo html_writer::tag('th', 'Date');
    echo html_writer::tag('th', 'Type');
    echo html_writer::tag('th', 'Message');
    echo html_writer::end_tag('tr');
    echo html_writer::end_tag('thead');
    echo html_writer::start_tag('tbody');

    foreach ($logs as $log) {
        $date = userdate($log->eventtime);
        $type = ucfirst($log->eventtype);
        $message = format_text($log->message, FORMAT_PLAIN);

        echo html_writer::start_tag('tr');
        echo html_writer::tag('td', $date);
        echo html_writer::tag('td', $type);
        echo html_writer::tag('td', $message);
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('tbody');
    echo html_writer::end_tag('table');

    // Pagination controls
    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url->out(false, ['logtype' => $logtype]));
} else {
    echo html_writer::div('No log entries found.', 'alert alert-info');
}

// [Since v1] Output end
echo $OUTPUT->footer();

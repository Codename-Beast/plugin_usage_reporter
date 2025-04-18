<?php
/**
 * v1.1.1-10 J 2025-04-13 [Initial API Handler]
 *
 * API Handler class for external API interactions.
 * Deprecated in favor of the Moodle Webservice API for v2.2
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\external;

defined('MOODLE_INTERNAL') || die();

use local_pluginusagereporter\ErrorHandler;
use local_pluginusagereporter\logger;

class api_handler
{
    /**
     * Sends the plugin usage report to an external API.
     *
     * @param array $report The report data to send.
     * @return bool True if successful, false otherwise.
     */
    public function send_report(array $report): bool
    {
        $apiUrl = get_config('local_pluginusagereporter', 'external_api_url');
        $apiKey = get_config('local_pluginusagereporter', 'external_api_key');

        // Validate configuration
        if (empty($apiUrl) || empty($apiKey)) {
            logger::add('error', 'API configuration missing: URL or API key not set.');
            return false;
        }

        // Prepare request payload
        $payload = json_encode([
            'apikey' => $apiKey,
            'report' => $report
        ]);

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Execute request
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Handle cURL error
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            logger::add('error', "API request failed: {$error}");
            (new ErrorHandler())->handle(new \Exception($error));

            return false;
        }

        curl_close($ch);

        // Check response
        if ($httpcode >= 200 && $httpcode < 300) {
            logger::add('success', "API report successfully sent. HTTP Status: {$httpcode}");
            return true;
        } else {
            logger::add('error', "API request failed. HTTP Status: {$httpcode}, Response: {$response}");
            (new ErrorHandler())->handle(new \Exception("HTTP {$httpcode}: {$response}"));

            return false;
        }
    }
}

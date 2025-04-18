<?php
/**
 *  [Initial Class Creation]
 *
 * ErrorHandler class.
 * v1.1.1-10 I 2025-04-18 [Error Handling Improvements]
 * Features:
 * - Centralized error handling for the Plugin Usage Reporter.
 * - Logs all errors using Moodle's Logging API.
 * - Prepares for future extensions (e.g., sending admin notifications, custom logging).
 * - Fully integrated into DataFetchers and future components.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

 namespace local_pluginusagereporter;

 class ErrorHandler {
 
     /**
      * [Since v1] Handles throwable errors with logging and optional fallback messaging.
      *
      * @param \Throwable $e
      * @return void
      */
     public function handle(\Throwable $e): void {
         // Clean up sensitive data (basic placeholder, can be expanded).
         $message = $this->sanitize_message($e->getMessage());
 
         // Developer mode debugging.
         debugging('Plugin Usage Reporter Error: ' . $message, DEBUG_DEVELOPER);
 
         // Moodle log entry.
         \core\notification::add('An error occurred. Check site logs.', \core\output\notification::NOTIFY_ERROR);
 
         // Custom plugin log (db table if needed).
         logger::add('error', $message, ['file' => $e->getFile(), 'line' => $e->getLine()]);
     }
 
     /**
      * [Since v1.1.1-10 I] Basic message sanitization (prevent full SQL leakage).
      *
      * @param string $message
      * @return string
      */
     private function sanitize_message(string $message): string {
         // Remove potential SQL or token-like data
         $patterns = ['/SELECT .* FROM/i', '/INSERT INTO/i', '/UPDATE .* SET/i', '/DELETE FROM/i'];
         return preg_replace($patterns, '[SQL]', $message);
     }
 }
 

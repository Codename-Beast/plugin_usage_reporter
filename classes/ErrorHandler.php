<?php
/**
 *  [Initial Class Creation]
 *
 * ErrorHandler class.
 *
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

defined('MOODLE_INTERNAL') || die();

use Throwable;
use moodle_exception;
use local_pluginusagereporter\logger;
use local_pluginusagereporter\notifier;

class ErrorHandler
{
    /**
     * Handles any throwable error or exception.
     *
     * @param Throwable $exception
     * @return void
     */
    public function handle(Throwable $exception): void
    {
        $message = sprintf(
            'Error: %s in %s on line %d',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        // Log error to Moodle debugging
        debugging($message, DEBUG_DEVELOPER);

       // Advanced Moodle logging or notifications
        $this->log_to_moodle($message, $exception);

        // Log error to custom logger
        logger::add('error', $exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);

        // [Since v1.1.1-10 E] Send email notification if enabled
        notifier::send(
            'Plugin Usage Reporter Error',
            $exception->getMessage()
        );

        // Optional: Rethrow the exception if needed
        // throw $exception;
    }

    /**
     * Logs the error details using Moodle logging.
     *
     * @param string $message
     * @param Throwable|null $exception
     * @return void
     */
    private function log_to_moodle(string $message, ?Throwable $exception = null): void
    {
        if (PHPUNIT_TEST) {
            // Skip logging in PHPUnit tests
            return;
        }

        \core\notification::error($message);

        if ($exception instanceof moodle_exception) {
            // Optionally extend for moodle_exception specifics
            debugging('Moodle Exception Debug Data: ' . json_encode($exception->getDebugInfo()), DEBUG_DEVELOPER);
        }
    }

}

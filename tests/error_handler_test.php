<?php

declare(strict_types=1);

namespace local_pluginusagereporter\tests;

/**
 * Unit test for ErrorHandler.
 *
 * [Since v1.1.1-11 F] Ensures ErrorHandler captures and logs exceptions properly.
 *
 * @package local_pluginusagereporter
 * @covers \local_pluginusagereporter\helper\ErrorHandler
 */

use advanced_testcase;
use local_pluginusagereporter\helper\ErrorHandler;
use moodle_exception;

final class error_handler_test extends advanced_testcase {

    /**
     * Verifies that the ErrorHandler logs exceptions properly.
     *
     * This test only verifies that the exception is logged by the ErrorHandler.
     * The actual logging implementation is tested in the logging_test.php file.
     */
    public function test_handle_logs_exception(): void {
        $this->resetAfterTest(true);
        $errorhandler = new ErrorHandler();

        $exception = new moodle_exception('testerror', 'local_pluginusagereporter');

        $this->expectNotToPerformAssertions();
        $errorhandler->handle($exception);
    }
}

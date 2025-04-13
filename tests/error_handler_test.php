<?php
/**
 * [Initial Unit Test for ErrorHandler]
 *
 * Unit tests for ErrorHandler class.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

namespace local_pluginusagereporter\tests;

use advanced_testcase;
use local_pluginusagereporter\ErrorHandler;

defined('MOODLE_INTERNAL') || die();

final class error_handler_test extends advanced_testcase
{
    protected function setUp(): void
    {
        $this->resetAfterTest();
    }

    public function test_handle_with_exception(): void
    {
        $errorHandler = new ErrorHandler();

        $exception = new \Exception('Test exception for ErrorHandler');
        $this->expectNotToPerformAssertions();

        $errorHandler->handle($exception);
    }

    public function test_handle_with_moodle_exception(): void
    {
        $errorHandler = new ErrorHandler();

        $moodleException = new \moodle_exception('testcode', 'local_pluginusagereporter', '', null, 'Test moodle exception');
        $this->expectNotToPerformAssertions();

        $errorHandler->handle($moodleException);
    }

    public function test_handle_with_throwable(): void
    {
        $errorHandler = new ErrorHandler();

        $throwable = new class extends \Error {
            public function __construct() {
                parent::__construct('Test throwable error');
            }
        };

        $this->expectNotToPerformAssertions();

        $errorHandler->handle($throwable);
    }
}

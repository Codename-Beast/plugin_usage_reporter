<?php
/**
 * GeneratorFactory – Factory class for creating report generators.
 *
 * @package    local_pluginusagereporter
 * @category   generator
 * @author     Bernd Schreistetter
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @version    v2.3.0
 */

namespace local_pluginusagereporter\generator;

defined('MOODLE_INTERNAL') || die();

use moodle_exception;

class GeneratorFactory
{
    /**
     * Creates a report generator instance based on the given format.
     *
     * @param string $format The output format (csv, html, txt, json, xml)
     * @return GeneratorInterface
     * @throws moodle_exception If the format is unsupported
     */
    public static function make(string $format): GeneratorInterface
    {
        $format = strtolower(trim($format));

        return match ($format) {
            'csv'  => new CsvReportGenerator(),
            'html' => new HtmlReportGenerator(),
            'txt'  => new TextReportGenerator(),
            'json' => new JsonReportGenerator(),
            'xml'  => new XmlReportGenerator(),
            default => throw new moodle_exception('error_invalidformat', 'local_pluginusagereporter', '', null, $format),
        };
    }
}

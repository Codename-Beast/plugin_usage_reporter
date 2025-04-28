<?php
/**
 * Capability definitions for the plugin usage reporter plugin.
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT 
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    // Capability to view plugin usage reports
    'local/pluginusagereporter:view' => array(
        'riskbitmask' => RISK_PERSONAL,  // Risk of viewing personal data
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        )
    ),

    // Capability to configure plugin settings
    'local/pluginusagereporter:config' => array(
        'riskbitmask' => RISK_CONFIG,    // Risk of changing system configuration
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        )
    ),

    // Capability to execute the report task manually
    'local/pluginusagereporter:execute' => array(
        'riskbitmask' => RISK_CONFIG | RISK_DATALOSS,   // Risk of data loss or configuration changes
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        )
    ),

    // Capability to receive reports via email
    'local/pluginusagereporter:receivereports' => array(
        'riskbitmask' => RISK_PERSONAL,  // Risk of receiving personal data
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        )
    )
);
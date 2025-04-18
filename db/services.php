<?php

/**
 * Defines external functions and web services.
 *
 * @since v1.1.1-11 B
 * @package local_pluginusagereporter
 */

$functions = [
    'local_pluginusagereporter_get_plugin_usage_data' => [
        'classname'   => 'local_pluginusagereporter\\external\\report_api_service',
        'methodname'  => 'get_plugin_usage_data',
        'classpath'   => '', // not needed because the class is already loaded through the PSR-4 autoloader.
        'description' => 'Returns plugin usage data for visible courses.',
        'type'        => 'read',
        'capabilities'=> 'local/pluginusagereporter:view',
        'ajax'        => true,
    ],
];

$services = [
    'Plugin Usage Reporter Service' => [
        'functions' => [
            'local_pluginusagereporter_get_plugin_usage_data',
        ],
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'pluginusagereporter_ws'
    ]
];

<?php

return [

    'enabled' => env('MCA_FIREWALL_ENABLED', true),

    'locale' => env('MCA_FIREWALL_LOCALE'),

    'table' => env('MCA_FIREWALL_TABLE', 'mca_firewall_rules'),

    'cache' => [
        'enabled' => env('MCA_FIREWALL_CACHE', true),
        'ttl' => (int) env('MCA_FIREWALL_CACHE_TTL', 300),
        'key' => 'mca.firewall.rules',
    ],

    /*
    |--------------------------------------------------------------------------
    | Request protection middleware
    |--------------------------------------------------------------------------
    |
    | When auto_register is true, BlockBlacklistedIp is pushed onto the listed
    | middleware groups (after boot). Whitelisted IPs always pass.
    |
    */
    'protection' => [
        'enabled' => env('MCA_FIREWALL_PROTECTION', true),
        'auto_register' => env('MCA_FIREWALL_AUTO_REGISTER', true),
        'groups' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('MCA_FIREWALL_GROUPS', 'web,api'))
        ))),
        'block_status' => (int) env('MCA_FIREWALL_BLOCK_STATUS', 403),
        'record_hits' => env('MCA_FIREWALL_RECORD_HITS', true),
    ],

    'routes' => [
        'load_package_routes' => env('MCA_FIREWALL_LOAD_ROUTES', true),
        'web' => [
            'prefix' => env('MCA_FIREWALL_ROUTE_PREFIX', 'mca/firewall'),
            'middleware' => array_filter(explode(',', (string) env(
                'MCA_FIREWALL_MIDDLEWARE',
                'web,auth,mca.firewall.root,mca.firewall.locale'
            ))),
            'name_prefix' => 'mca.firewall.',
        ],
    ],

    'controllers' => [
        'web' => [
            'rules' => \Mca\Firewall\Http\Controllers\Web\RuleController::class,
        ],
    ],

    'views' => [
        'namespace' => env('MCA_FIREWALL_VIEW_NAMESPACE', 'mca-firewall'),
        'layout' => env('MCA_FIREWALL_VIEW_LAYOUT', 'mca-firewall::layouts.app'),
    ],

    'ui' => [
        'title' => env('MCA_FIREWALL_UI_TITLE'),
        'class_prefix' => 'mca-fw',
        'assets' => [
            'css' => 'vendor/mca-firewall/mca-firewall.css',
            'js' => 'vendor/mca-firewall/mca-firewall.js',
            'ui' => 'vendor/mca-permission/mca-ui.css',
            'ui_js' => 'vendor/mca-permission/mca-ui.js',
        ],
    ],

    'access' => [
        'use_permission_root' => env('MCA_FIREWALL_USE_PERMISSION_ROOT', true),
        'role_column' => env('MCA_FIREWALL_ROLE_COLUMN', 'role_id'),
        'root_role' => env('MCA_FIREWALL_ROOT_ROLE', 'root'),
    ],

];

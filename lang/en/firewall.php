<?php

return [
    'app' => [
        'title' => 'Firewall',
        'brand' => 'Firewall',
        'nav_aria' => 'Firewall navigation',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'rules' => 'Rules',
        'trash' => 'Trash',
        'create' => 'Add rule',
    ],
    'filters' => [
        'all' => 'All',
        'whitelist' => 'Whitelist',
        'blacklist' => 'Blacklist',
        'search' => 'Search IP, label…',
    ],
    'fields' => [
        'ip' => 'IP / CIDR',
        'ip_help' => 'Examples: 1.2.3.4 or 1.2.3.0/24',
        'type' => 'List',
        'label' => 'Label',
        'reason' => 'Reason',
        'is_active' => 'Active',
        'expires_at' => 'Expires at',
        'hits' => 'Hits',
        'last_hit' => 'Last hit',
        'created' => 'Created',
    ],
    'types' => [
        'whitelist' => 'Whitelist',
        'blacklist' => 'Blacklist',
    ],
    'actions' => [
        'save' => 'Save',
        'create' => 'Create rule',
        'edit' => 'Edit',
        'delete' => 'Move to trash',
        'restore' => 'Restore',
        'force_delete' => 'Delete forever',
        'toggle' => 'Toggle',
        'cancel' => 'Cancel',
        'back' => 'Back',
    ],
    'table' => [
        'empty' => 'No rules yet.',
        'trash_empty' => 'Trash is empty.',
        'status_active' => 'Active',
        'status_inactive' => 'Inactive',
        'status_expired' => 'Expired',
    ],
    'flash' => [
        'created' => 'Rule created.',
        'updated' => 'Rule updated.',
        'trashed' => 'Rule moved to trash.',
        'restored' => 'Rule restored.',
        'force_deleted' => 'Rule permanently deleted.',
        'toggled' => 'Rule status updated.',
    ],
    'confirm' => [
        'trash' => 'Move this rule to trash?',
        'force_delete' => 'Permanently delete this rule? This cannot be undone.',
        'restore' => 'Restore this rule?',
    ],
    'errors' => [
        'root_only' => 'Only root users can manage the firewall.',
        'blocked' => 'Your IP address is blocked.',
    ],
    'validation' => [
        'ip_invalid' => 'Enter a valid IPv4/IPv6 address or CIDR range.',
    ],
    'modal' => [
        'ok' => 'OK',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'alert_title' => 'Notice',
        'confirm_title' => 'Confirm',
    ],
    'console' => [
        'install' => [
            'start' => 'Installing MCA Firewall…',
            'config_ready' => 'Config ready',
            'assets_published' => 'Assets published',
            'migration_done' => 'Migrations done',
            'done' => 'MCA Firewall installed.',
            'web_ui' => 'Web UI: /:prefix',
        ],
    ],
    'pages' => [
        'index_title' => 'IP rules',
        'create_title' => 'Add IP rule',
        'edit_title' => 'Edit IP rule',
        'trash_title' => 'Trash',
    ],
    'hint' => [
        'suite' => 'Access suite: pair with mca/access-log and mca/access-intel for request history and health scoring.',
        'proxy' => 'Behind a proxy/CDN, configure Laravel TrustProxies so client IPs resolve correctly.',
    ],
];

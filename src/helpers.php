<?php

use Mca\Firewall\Services\FirewallService;

if (! function_exists('mca_firewall')) {
    function mca_firewall(): FirewallService
    {
        return app(FirewallService::class);
    }
}

if (! function_exists('mca_fw')) {
    /** @param  array<string, string|int>  $replace */
    function mca_fw(string $key, array $replace = []): string
    {
        return (string) __('mca-firewall::firewall.'.$key, $replace);
    }
}

if (! function_exists('mca_firewall_allows')) {
    function mca_firewall_allows(?string $ip = null): bool
    {
        return mca_firewall()->allows($ip);
    }
}

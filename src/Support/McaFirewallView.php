<?php

namespace Mca\Firewall\Support;

use Illuminate\Contracts\View\View;

final class McaFirewallView
{
    public static function layout(): string
    {
        return (string) config('firewall.views.layout', 'mca-firewall::layouts.app');
    }

    public static function render(string $view, array $data = []): View
    {
        McaFirewallLocale::apply();

        $namespace = config('firewall.views.namespace', 'mca-firewall');

        return view($namespace.'::'.$view, array_merge([
            'mcaFwTitle' => config('firewall.ui.title') ?: mca_fw('app.title'),
        ], $data));
    }

    public static function uiCssUrl(): string
    {
        return asset((string) config('firewall.ui.assets.ui', 'vendor/mca-permission/mca-ui.css'));
    }

    public static function uiJsUrl(): string
    {
        return asset((string) config('firewall.ui.assets.ui_js', 'vendor/mca-permission/mca-ui.js'));
    }

    public static function cssUrl(): string
    {
        return asset((string) config('firewall.ui.assets.css', 'vendor/mca-firewall/mca-firewall.css'));
    }

    public static function jsUrl(): string
    {
        return asset((string) config('firewall.ui.assets.js', 'vendor/mca-firewall/mca-firewall.js'));
    }
}

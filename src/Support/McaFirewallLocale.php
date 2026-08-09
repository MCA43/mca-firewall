<?php

namespace Mca\Firewall\Support;

final class McaFirewallLocale
{
    public static function resolve(): string
    {
        $locale = config('firewall.locale');

        if (is_string($locale) && $locale !== '') {
            return $locale;
        }

        return (string) app()->getLocale();
    }

    public static function apply(): void
    {
        app()->setLocale(self::resolve());
    }
}

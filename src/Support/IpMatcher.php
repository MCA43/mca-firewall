<?php

namespace Mca\Firewall\Support;

final class IpMatcher
{
    public static function isValid(string $value): bool
    {
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        if (str_contains($value, '/')) {
            [$ip, $mask] = explode('/', $value, 2);

            if (! is_numeric($mask)) {
                return false;
            }

            $mask = (int) $mask;

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $mask >= 0 && $mask <= 32;
            }

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return $mask >= 0 && $mask <= 128;
            }

            return false;
        }

        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    public static function matches(string $clientIp, string $ruleIp): bool
    {
        $clientIp = trim($clientIp);
        $ruleIp = trim($ruleIp);

        if ($clientIp === '' || $ruleIp === '') {
            return false;
        }

        if (! filter_var($clientIp, FILTER_VALIDATE_IP)) {
            return false;
        }

        if (! str_contains($ruleIp, '/')) {
            return self::normalize($clientIp) === self::normalize($ruleIp);
        }

        [$subnet, $mask] = explode('/', $ruleIp, 2);
        $mask = (int) $mask;

        if (! filter_var($subnet, FILTER_VALIDATE_IP)) {
            return false;
        }

        $clientBin = self::ipToBits($clientIp);
        $subnetBin = self::ipToBits($subnet);

        if ($clientBin === null || $subnetBin === null || strlen($clientBin) !== strlen($subnetBin)) {
            return false;
        }

        $max = strlen($clientBin);
        $mask = max(0, min($mask, $max));

        return substr($clientBin, 0, $mask) === substr($subnetBin, 0, $mask);
    }

    public static function normalize(string $ip): string
    {
        $ip = trim($ip);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $packed = inet_pton($ip);

            return $packed === false ? strtolower($ip) : strtolower((string) inet_ntop($packed));
        }

        return $ip;
    }

    private static function ipToBits(string $ip): ?string
    {
        $packed = @inet_pton($ip);

        if ($packed === false) {
            return null;
        }

        $bits = '';
        foreach (str_split($packed) as $char) {
            $bits .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        return $bits;
    }
}

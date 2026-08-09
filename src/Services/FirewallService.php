<?php

namespace Mca\Firewall\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Mca\Firewall\Events\IpBlocked;
use Mca\Firewall\Events\RuleChanged;
use Mca\Firewall\Models\FirewallRule;
use Mca\Firewall\Support\IpMatcher;

class FirewallService
{
    public const LIST_WHITELIST = 'whitelist';

    public const LIST_BLACKLIST = 'blacklist';

    /** @return Collection<int, FirewallRule> */
    public function activeRules(): Collection
    {
        if (! config('firewall.cache.enabled', true)) {
            return $this->queryActiveRules();
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = Cache::remember(
            (string) config('firewall.cache.key', 'mca.firewall.rules'),
            (int) config('firewall.cache.ttl', 300),
            fn () => $this->queryActiveRules()->map(fn (FirewallRule $r) => $r->toArray())->all(),
        );

        return collect($rows)->map(function (array $row): FirewallRule {
            $rule = new FirewallRule;
            $rule->forceFill($row);
            $rule->exists = true;

            return $rule;
        });
    }

    public function forgetCache(): void
    {
        Cache::forget((string) config('firewall.cache.key', 'mca.firewall.rules'));
    }

    public function whichList(?string $ip = null): ?string
    {
        $ip = $this->resolveIp($ip);

        if ($ip === null) {
            return null;
        }

        $matchedWhitelist = null;
        $matchedBlacklist = null;

        foreach ($this->activeRules() as $rule) {
            if (! IpMatcher::matches($ip, (string) $rule->ip)) {
                continue;
            }

            if ($rule->type === FirewallRule::TYPE_WHITELIST) {
                $matchedWhitelist = $rule;
                break;
            }

            if ($rule->type === FirewallRule::TYPE_BLACKLIST && $matchedBlacklist === null) {
                $matchedBlacklist = $rule;
            }
        }

        if ($matchedWhitelist !== null) {
            $this->touchHit($matchedWhitelist);

            return self::LIST_WHITELIST;
        }

        if ($matchedBlacklist !== null) {
            $this->touchHit($matchedBlacklist);

            return self::LIST_BLACKLIST;
        }

        return null;
    }

    public function isWhitelisted(?string $ip = null): bool
    {
        return $this->whichList($ip) === self::LIST_WHITELIST;
    }

    public function isBlacklisted(?string $ip = null): bool
    {
        return $this->whichList($ip) === self::LIST_BLACKLIST;
    }

    public function allows(?string $ip = null): bool
    {
        return ! $this->isBlacklisted($ip);
    }

    public function evaluate(Request $request): bool
    {
        if (! config('firewall.enabled', true) || ! config('firewall.protection.enabled', true)) {
            return true;
        }

        $ip = $request->ip();
        $list = $this->whichList($ip);

        if ($list === self::LIST_BLACKLIST) {
            event(new IpBlocked((string) $ip, $request->path(), $request->method()));

            return false;
        }

        return true;
    }

    /** @param  array<string, mixed>  $data */
    public function create(array $data, ?int $userId = null): FirewallRule
    {
        $rule = FirewallRule::query()->create([
            'ip' => trim((string) $data['ip']),
            'type' => (string) $data['type'],
            'label' => $data['label'] ?? null,
            'reason' => $data['reason'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'expires_at' => $data['expires_at'] ?? null,
            'created_by' => $userId,
        ]);

        $this->forgetCache();
        event(new RuleChanged('created', $rule));

        return $rule;
    }

    /** @param  array<string, mixed>  $data */
    public function update(FirewallRule $rule, array $data): FirewallRule
    {
        $rule->fill([
            'ip' => array_key_exists('ip', $data) ? trim((string) $data['ip']) : $rule->ip,
            'type' => $data['type'] ?? $rule->type,
            'label' => array_key_exists('label', $data) ? $data['label'] : $rule->label,
            'reason' => array_key_exists('reason', $data) ? $data['reason'] : $rule->reason,
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : $rule->is_active,
            'expires_at' => array_key_exists('expires_at', $data) ? $data['expires_at'] : $rule->expires_at,
        ]);
        $rule->save();

        $this->forgetCache();
        event(new RuleChanged('updated', $rule));

        return $rule;
    }

    public function delete(FirewallRule $rule): void
    {
        $rule->delete();
        $this->forgetCache();
        event(new RuleChanged('deleted', $rule));
    }

    public function restore(FirewallRule $rule): void
    {
        $rule->restore();
        $this->forgetCache();
        event(new RuleChanged('restored', $rule));
    }

    public function forceDelete(FirewallRule $rule): void
    {
        $rule->forceDelete();
        $this->forgetCache();
        event(new RuleChanged('force_deleted', $rule));
    }

    public function toggle(FirewallRule $rule): FirewallRule
    {
        $rule->is_active = ! $rule->is_active;
        $rule->save();
        $this->forgetCache();
        event(new RuleChanged('toggled', $rule));

        return $rule;
    }

    /** @param  array<string, mixed>  $attributes */
    public function whitelist(string $ip, array $attributes = [], ?int $userId = null): FirewallRule
    {
        return $this->create(array_merge($attributes, [
            'ip' => $ip,
            'type' => FirewallRule::TYPE_WHITELIST,
        ]), $userId);
    }

    /** @param  array<string, mixed>  $attributes */
    public function blacklist(string $ip, array $attributes = [], ?int $userId = null): FirewallRule
    {
        return $this->create(array_merge($attributes, [
            'ip' => $ip,
            'type' => FirewallRule::TYPE_BLACKLIST,
        ]), $userId);
    }

    /** @return Collection<int, FirewallRule> */
    private function queryActiveRules(): Collection
    {
        return FirewallRule::query()
            ->active()
            ->orderByRaw("CASE WHEN type = 'whitelist' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->get();
    }

    private function resolveIp(?string $ip): ?string
    {
        if ($ip === null || $ip === '') {
            $ip = request()?->ip();
        }

        if (! is_string($ip) || $ip === '' || ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }

        return $ip;
    }

    private function touchHit(FirewallRule $rule): void
    {
        if (! config('firewall.protection.record_hits', true) || ! $rule->exists || $rule->getKey() === null) {
            return;
        }

        FirewallRule::query()->whereKey($rule->getKey())->update([
            'hits' => $rule->hits + 1,
            'last_hit_at' => now(),
        ]);
    }
}

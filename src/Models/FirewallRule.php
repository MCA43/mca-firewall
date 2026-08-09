<?php

namespace Mca\Firewall\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirewallRule extends Model
{
    use SoftDeletes;

    public const TYPE_WHITELIST = 'whitelist';

    public const TYPE_BLACKLIST = 'blacklist';

    protected $fillable = [
        'ip',
        'type',
        'label',
        'reason',
        'is_active',
        'expires_at',
        'hits',
        'last_hit_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'last_hit_at' => 'datetime',
            'hits' => 'integer',
        ];
    }

    public function getTable(): string
    {
        return (string) config('firewall.table', 'mca_firewall_rules');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isEffective(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }
}

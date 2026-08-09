<?php

namespace Mca\Firewall\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Mca\Firewall\Models\FirewallRule;

class RuleChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $action,
        public readonly FirewallRule $rule,
    ) {}
}

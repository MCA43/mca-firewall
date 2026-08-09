<?php

namespace Mca\Firewall\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IpBlocked
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $ip,
        public readonly string $path,
        public readonly string $method,
    ) {}
}

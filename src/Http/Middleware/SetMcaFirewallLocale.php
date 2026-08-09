<?php

namespace Mca\Firewall\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\Firewall\Support\McaFirewallLocale;
use Symfony\Component\HttpFoundation\Response;

class SetMcaFirewallLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        McaFirewallLocale::apply();

        return $next($request);
    }
}

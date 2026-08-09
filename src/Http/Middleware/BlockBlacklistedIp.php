<?php

namespace Mca\Firewall\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\Firewall\Services\FirewallService;
use Symfony\Component\HttpFoundation\Response;

class BlockBlacklistedIp
{
    public function __construct(private readonly FirewallService $firewall) {}

    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->excludedPathPatterns() as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        if (! $this->firewall->evaluate($request)) {
            abort(
                (int) config('firewall.protection.block_status', 403),
                mca_fw('errors.blocked'),
            );
        }

        return $next($request);
    }

    /** @return list<string> */
    private function excludedPathPatterns(): array
    {
        $prefix = trim((string) config('firewall.routes.web.prefix', 'mca/firewall'), '/');
        $patterns = [];

        if ($prefix !== '') {
            $patterns[] = $prefix;
            $patterns[] = $prefix.'/*';
        }

        // Access suite admin UIs stay reachable so root can unblock / inspect.
        $patterns[] = 'mca/access-log';
        $patterns[] = 'mca/access-log/*';
        $patterns[] = 'mca/access-intel';
        $patterns[] = 'mca/access-intel/*';

        return $patterns;
    }
}

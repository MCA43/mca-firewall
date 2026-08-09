<?php

namespace Mca\Firewall;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Mca\Firewall\Console\InstallFirewallCommand;
use Mca\Firewall\Http\Middleware\BlockBlacklistedIp;
use Mca\Firewall\Http\Middleware\EnsureMcaFirewallRoot;
use Mca\Firewall\Http\Middleware\SetMcaFirewallLocale;
use Mca\Firewall\Services\FirewallService;

class FirewallServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/firewall.php', 'firewall');
        $this->app->singleton(FirewallService::class);
    }

    public function boot(): void
    {
        if (! config('firewall.enabled', true)) {
            return;
        }

        $this->registerPublishing();
        $this->registerMiddleware();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'mca-firewall');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mca-firewall');
        $this->registerRoutes();
        $this->registerProtection();
        $this->registerHub();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallFirewallCommand::class,
            ]);
        }
    }

    protected function registerHub(): void
    {
        if (! function_exists('mca_hub_register')) {
            return;
        }

        mca_hub_register('firewall', [
            'enabled' => fn () => (bool) config('firewall.enabled', true),
        ]);
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/firewall.php' => config_path('firewall.php'),
        ], 'mca-firewall-config');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mca-firewall'),
        ], 'mca-firewall-assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/mca-firewall'),
        ], 'mca-firewall-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'mca-firewall-migrations');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('mca.firewall.root', EnsureMcaFirewallRoot::class);
        $router->aliasMiddleware('mca.firewall.locale', SetMcaFirewallLocale::class);
        $router->aliasMiddleware('mca.firewall', BlockBlacklistedIp::class);
    }

    protected function registerRoutes(): void
    {
        if (! config('firewall.routes.load_package_routes', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    protected function registerProtection(): void
    {
        if (! config('firewall.protection.enabled', true) || ! config('firewall.protection.auto_register', true)) {
            return;
        }

        $this->app->booted(function (): void {
            /** @var Router $router */
            $router = $this->app['router'];
            $groups = config('firewall.protection.groups', ['web', 'api']);

            if (! is_array($groups)) {
                return;
            }

            foreach ($groups as $group) {
                if (is_string($group) && $group !== '') {
                    $router->pushMiddlewareToGroup($group, BlockBlacklistedIp::class);
                }
            }
        });
    }
}

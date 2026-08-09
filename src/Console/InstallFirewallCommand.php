<?php

namespace Mca\Firewall\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Mca\Firewall\Support\McaFirewallLocale;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'mca:firewall:install')]
class InstallFirewallCommand extends Command
{
    protected $signature = 'mca:firewall:install
                            {--no-assets : Skip CSS publish}';

    protected $description = 'Install MCA Firewall (migration, assets)';

    public function handle(): int
    {
        McaFirewallLocale::apply();

        $this->components->info(mca_fw('console.install.start'));

        if (! file_exists(config_path('firewall.php'))) {
            $this->callSilent('vendor:publish', ['--tag' => 'mca-firewall-config']);
        }
        $this->components->task(mca_fw('console.install.config_ready'), fn () => true);

        if (! $this->option('no-assets')) {
            $this->callSilent('vendor:publish', [
                '--tag' => 'mca-firewall-assets',
                '--force' => true,
            ]);
            $this->components->task(mca_fw('console.install.assets_published'), fn () => true);
        }

        Artisan::call('migrate', ['--force' => true]);
        $this->output->write(Artisan::output());
        $this->components->task(mca_fw('console.install.migration_done'), fn () => true);

        $this->newLine();
        $this->components->info(mca_fw('console.install.done'));
        $this->line('  '.mca_fw('console.install.web_ui', [
            'prefix' => config('firewall.routes.web.prefix', 'mca/firewall'),
        ]));

        return self::SUCCESS;
    }
}

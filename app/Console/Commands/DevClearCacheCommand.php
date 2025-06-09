<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DevClearCacheCommand extends Command
{
    protected $signature = 'dev:clear-cache';

    public function handle(): void
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->info(__('commands.dev_clear_cache'));
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes the application by calling various import commands.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('app:update-icons-fill');
        $this->call('app:import-icons');
        $this->call('app:import-words');
        $this->call('app:import-messages');

        $this->call('app:import-properties');
        $this->call('app:import-games');

        $this->info('Application has been successfully initialized!');
    }
}

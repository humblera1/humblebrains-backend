<?php

namespace App\Console\Commands\Icons;

use Illuminate\Console\Command;

class LinkIconsCommand extends Command
{
    protected $signature = 'app:link-icons';

    protected $description = 'Create a symbolic link from "public/icons" to "storage/data/icons"';

    public function handle(): void
    {
        $target = storage_path('data/icons');
        $link = public_path('icons');

        if (file_exists($link)) {
            $this->info('The "public/icons" directory already exists.');
            return;
        }

        try {
            symlink($target, $link);
            $this->info('The symbolic link has been created.');
        } catch (\Exception $e) {
            $this->error('Failed to create symbolic link: ' . $e->getMessage());
        }
    }
}

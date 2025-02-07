<?php

namespace App\Console\Commands\Icons;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing SVG icons into the database';

    private string $iconsTable = 'icons';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = File::files(storage_path('data/icons'));

        if (empty($files)) {
            $this->error('No icons found in the directory.');

            return;
        }

        $batchSize = 100;
        $iconsData = [];

        foreach ($files as $file) {

            $name = pathinfo($file, PATHINFO_FILENAME);

            $iconsData[] = [
                'name' => $name,
                'path' => "icons/{$file->getFilename()}",
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($iconsData) >= $batchSize) {
                $this->insertBatch($iconsData);
                $iconsData = [];
            }
        }

        if (!empty($iconsData)) {
            $this->insertBatch($iconsData);
        }

        $this->info('All icons have been imported successfully.');
    }

    private function insertBatch(array $iconsData)
    {
        DB::table($this->iconsTable)->upsert(
            $iconsData,
            ['name'],
            ['path', 'updated_at']
        );
    }
}

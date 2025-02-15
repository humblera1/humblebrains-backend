<?php

namespace App\Console\Commands\Icons;

use App\Services\Api\IconService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateIconsFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-icons-fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(IconService $service): void
    {
        $iconsPath = storage_path('data/icons');

        if (!File::exists($iconsPath)) {
            $this->error("The directory {$iconsPath} does not exist.");

            return;
        }

        $files = File::files($iconsPath);

        if (empty($files)) {
            $this->error('No icons found in the directory.');

            return;
        }

        $this->output->progressStart(count($files));

        foreach ($files as $file) {
            $mimeType = File::mimeType($file->getRealPath());

            if ($mimeType !== 'image/svg+xml') {
                $this->warn("File {$file} is not SVG. MIME type: {$mimeType}");

                continue;
            }

            $svgContent = File::get($file);
            $updatedContent = $service->updateSvgFill($svgContent);

            if (!$updatedContent) {
                 $this->error("Failed to update SVG file {$file}.");

                 continue;
            }

            File::put($file, $updatedContent);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info("Updated " . count($files) . " icons!");
    }
}

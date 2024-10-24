<?php

namespace App\Console\Commands\Words;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import words from a file into the database';

    private string $wordsTable = 'words';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $filePath = storage_path('data/words.csv');

        if (!file_exists($filePath)) {
            $this->error("Can't find file with words: $filePath");

            return;
        }

        $batchSize = 100;
        $batchData = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $record = array_combine($header, $data);

                $wordTranslations = json_encode(
                    [
                        'en' => $record['en'],
                        'ru' => $record['ru'],
                    ],
                    JSON_UNESCAPED_UNICODE,
                );

                $batchData[] = [
                    'word' => $wordTranslations,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batchData) >= $batchSize) {
                    $this->insertBatch($batchData);
                    $batchData = [];
                }
            }

            if (!empty($batchData)) {
                $this->insertBatch($batchData);
            }

            fclose($handle);
        }

        $this->info('All words have been imported successfully.');
    }

    private function insertBatch(array $batchData)
    {
        DB::table($this->wordsTable)->insert($batchData);
    }
}

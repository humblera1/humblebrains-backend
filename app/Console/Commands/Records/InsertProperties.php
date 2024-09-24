<?php

namespace App\Console\Commands\Records;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InsertProperties extends AbstractRecordCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:insert-properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new games properties';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $properties = config('properties');

        foreach ($properties as $game => $gameProperties) {
            $this->info('Inserting properties of ' . $game . ':');

            foreach ($gameProperties as $property) {
                $this->prepareTranslatableData($property);

                $time = Carbon::now();

                $inserted = DB::table('properties')->insertOrIgnore(
                    [
                        ...$property,
                        'created_at' => $time,
                        'updated_at' => $time,
                    ],
                );

                $inserted
                    ? $this->info('The property was successfully inserted: ' . $property['name'])
                    : $this->warn('The property was skipped: ' . $property['name']);
            }
        }

        return 0;
    }
}

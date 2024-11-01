<?php

namespace App\Console\Commands\Records;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImportProperties extends AbstractRecordCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-properties {--mode=ignore : The mode of operation (skip|update)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new games properties or update existing ones';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $properties = config('properties.index');
        $mode = $this->option('mode');

        $time = Carbon::now();

        foreach ($properties as $property) {
            $this->prepareTranslatableData($property);

            $property['created_at'] = $time;
            $property['updated_at'] = $time;

            $alreadyExists = DB::table('properties')->where('name', $property['name'])->exists();

            if (!$alreadyExists) {
                 DB::table('properties')->insert($property);

                 continue;
            }

            $propertyName = $property['name'];

            if ($mode ===  self::UPDATE_MODE) {
                 DB::table('properties')->where('name', $propertyName)->update($property);

                 $this->warn("Property '{$propertyName}' already exists, updated!");
            } else {
                 $this->warn("Property '{$propertyName}' already exists, skipping.");
            }
        }

        $this->info('All properties have been imported successfully.');
    }
}

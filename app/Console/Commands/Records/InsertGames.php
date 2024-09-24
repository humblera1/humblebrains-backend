<?php

namespace App\Console\Commands\Records;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InsertGames extends AbstractRecordCommand
{
    private string $gamesTable = 'games';
    private string $propertiesTable = 'properties';

    private string $levelsTable = 'levels';

    private string $gameLevelPropertiesTable = 'game_level_properties';

    private string $configDirectory = 'games';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:insert-games {game?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new games';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($game = $this->argument('game')) {
            $this->insertSingleGame($game);

            return 0;
        }

        return 0;
    }

    protected function insertSingleGame(string $name)
    {
        $this->info('Inserting game ' . $name . '...');

        $configFileName = $this->configDirectory . '.' . $name;

        $data = config($configFileName);

        if (is_null($data)) {
            $this->error("Can't find configuration file, skipping");

            return;
        }

        if (!array_key_exists('levels', $data)) {
            $this->error("The 'levels' key must be set in the configuration file, skipping");

            return;
        }

        // Retrieving levels array
        $levels = $data['levels'];

        // Now deleting this key from array
        unset($data['levels']);

        DB::beginTransaction();

        $now = Carbon::now();

        // todo: updateOrInsert method
        if (DB::table($this->gamesTable)->where('name', $name)->exists()) {
            if ($this->confirm('Game already exists, update?')) {
                DB::table('games')->update(
                    [
                        ...$data,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );
            }
        } else {
            DB::table('games')->insert(
                [
                    ...$data,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        $gameId = DB::table($this->gamesTable)->where('name', $name)->value('id');

        $oneLevel = $levels[array_key_first($levels)];
        $propertyNames = array_keys($oneLevel);

        $propertiesInfo = DB::table($this->propertiesTable)
            ->select('id', 'name')
            ->whereIn('name', $propertyNames)
            ->get();

        $propertyIds = $propertiesInfo->pluck('id', 'name')->toArray();

        $nonExistingProperties = array_diff(array_keys($propertyIds), $propertyNames);

        if (count($nonExistingProperties) !== 0) {
            $this->error("Next properties doesn't exists: " . implode(',', $nonExistingProperties) . ", skipping");

            DB::rollback();

            return;
        }

        // and finally... Upserting levels

        foreach ($levels as $number => $levelData) {
            DB::table($this->levelsTable)->insertOrIgnore([
                'game_id' => $gameId,
                'level_number' => $number,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $levelId = DB::table($this->levelsTable)->where(['game_id' => $gameId, 'level_number' => $number])->value('id');

            foreach ($levelData as $property => $value) {
//                DB::table($this->gameLevelPropertiesTable)->updateOrInsert([
//                    'game_id' => $gameId,
//                    'level_id' => $levelId,
//                    'property_id' => $propertyIds[$property],
//                    'value' => $value,
//                ]);

                DB::table($this->gameLevelPropertiesTable)->upsert(
                    [
                        'game_id' => $gameId,
                        'level_id' => $levelId,
                        'property_id' => $propertyIds[$property],
                        'value' => $value,
                        'updated_at' => $now,
                    ],
                    [
                        'game_id',
                        'level_id',
                        'property_id',
                    ],
                    [
                        'value',
                        'updated_at',
                    ]
                );
            }
        }

        DB::commit();

        $this->info('Game ' . $name . ' successfully inserted!');
    }
}

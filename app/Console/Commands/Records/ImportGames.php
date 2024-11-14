<?php

namespace App\Console\Commands\Records;

use App\Services\Api\PointsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ImportGames extends AbstractRecordCommand
{
    protected PointsService $service;

    private string $gamesTable = 'games';
    private string $propertiesTable = 'properties';
    private string $categoriesTable = 'categories';
    private string $tutorialsTable = 'game_tutorials';
    private string $gameLevelPropertiesTable = 'game_level_properties';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-games {game?} {--mode=skip : The mode of operation (skip|update)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new games';

    public function __construct(PointsService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($game = $this->argument('game')) {
            $this->insertSingleGame(config("games.{$game}"));

            return;
        }

        $games = config('games');

        foreach ($games as $gameData) {
            $this->insertSingleGame($gameData);
        }
    }

    protected function insertSingleGame(array $gameData): void
    {
        $gameName = $gameData['name'];
        $alreadyExists = DB::table($this->gamesTable)->where('name', $gameName)->exists();

        if ($alreadyExists && $this->option('mode') ===  self::SKIP_MODE) {
            $this->warn("Game '{$gameName}' already exists, skipping.");

            return;
        }

        $this->info("Inserting game {$gameName}...");

        $levelsData = $gameData['levels'] ?? [];
        unset($gameData['levels']);

        $this->prepareTranslatableData($gameData);
        $this->prepareCategoryData($gameData);

        // todo: когда туториалы вырастут (будут представляться слайдерами на фронте), будет использоваться отдельный файл конфигов
        $tutorialsData['content'] =  $gameData['tutorial'] ?? '';
        unset($gameData['tutorial']);

        DB::transaction(function() use ($gameName, $gameData, $levelsData, $tutorialsData) {
            $inserted = DB::table($this->gamesTable)->updateOrInsert(['name' => $gameName], $gameData);

            $gameId = DB::table($this->gamesTable)->where('name', $gameName)->value('id');

            if  (!$inserted) {
                $this->error("Can't insert game '{$gameName}', skipping");

                throw new Exception();
            }

            if (!empty($tutorialsData)) {
                $tutorialsData['game_id'] = $gameId;
                $tutorialsData['created_at'] = now();
                $tutorialsData['updated_at'] = now();

                DB::table($this->tutorialsTable)->upsert($tutorialsData, ['id'], ['game_id', 'content', 'created_at', 'updated_at']);
            }

            if (!empty($levelsData)) {
                $propertyIds = DB::table($this->propertiesTable)
                    ->select('id', 'name')
                    ->pluck('id', 'name')
                    ->toArray();

                // check that all necessary properties exist in the database
                $propertyNames = array_keys($levelsData[array_key_first($levelsData)]);
                $nonExistingProperties = array_diff($propertyNames, array_keys($propertyIds));

                if (count($nonExistingProperties) !== 0) {
                    $this->error("Next properties doesn't exists: " . implode(',', $nonExistingProperties) . ", skipping");

                    throw new Exception();
                }

                // upserting levels
                $gameLevelPropertiesData = [];
                $pointsPerLevel = $this->service->calculatePointsPerAnswer($levelsData);

                foreach ($levelsData as $levelNumber => $levelData) {
                    if (!array_key_exists('points_per_answer', $levelData)) {
                        $levelData['points_per_answer'] = $pointsPerLevel[$levelNumber];
                    }

                    foreach ($levelData as $property => $value) {
                        $gameLevelPropertiesData[] = [
                            'game_id' => $gameId,
                            'property_id' => $propertyIds[$property],
                            'level' => $levelNumber,
                            'value' => $value,
                        ];
                    }
                }

                DB::table($this->gameLevelPropertiesTable)->upsert(
                    $gameLevelPropertiesData,
                    [
                        'game_id',
                        'level',
                        'property_id',
                    ],
                    [
                        'value',
                    ],
                );
            }
        });

        $this->info('Game ' . $gameName . ' successfully inserted!');
    }

    private function prepareCategoryData(array &$data): void
    {
        $categoryName = $data['category'];
        $categoryId = DB::table($this->categoriesTable)->where('name', $categoryName)->value('id');

        unset($data['category']);

        $data['category_id'] = $categoryId;
    }
}

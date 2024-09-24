<?php

namespace App\Console\Commands\Records;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InsertGames extends AbstractRecordCommand
{
    private string $gamesTable = 'games';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:insert-games';

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
        $games = config('games');

        foreach ($games as $gameName => $gameProperties) {
            $this->info('Inserting game ' . $gameName . '...');

            $this->prepareTranslatableData($gameProperties);

            $time = Carbon::now();

            $inserted = DB::table('games')->insertOrIgnore(
                [
                    ...$gameProperties,
                    'created_at' => $time,
                    'updated_at' => $time,
                ],
            );

            $inserted
                ? $this->info('The game was successfully inserted: ' . $gameName)
                : $this->warn('The game was skipped: ' . $gameName);
        }

        return 0;
    }
}

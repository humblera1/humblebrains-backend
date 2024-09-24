<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GameService
{
    public function getLevels(string $gameName): array
    {
        return DB::table('games as g')
            ->join(
                'game_level_properties as glp',
                'g.id',
                '=',
                'glp.game_id'
            )
            ->join(
                'levels as l',
                'g.id',
                '=',
                'l.game_id'
            )
            ->join(
                'properties as p',
                'glp.property_id',
                '=',
                'p.id'
            )
            ->select('glp.value', 'l.level_number', 'p.name')
            ->where('g.name', $gameName)
            ->orderBy('l.level_number')
            ->get()
            ->groupBy('level_number')
            ->map(function (Collection $items) {
                return $items->pluck('value', 'name');
            })
            ->toArray();
    }

}

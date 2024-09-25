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
                'l.id',
                '=',
                'glp.level_id'
            )
            ->join(
                'properties as p',
                'p.id',
                '=',
                'glp.property_id'
            )
            ->select('glp.value', 'l.level_number', 'p.name', 'p.type')
            ->where('g.name', $gameName)
            ->orderBy('l.level_number')
            ->get()
            ->groupBy('level_number')
            ->map(function (Collection $items) {
                return $items->pluckWithCast('type', 'value', 'name');
            })
            ->toArray();
    }

}

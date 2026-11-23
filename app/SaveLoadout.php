<?php

namespace App;

use App\Models\Loadout;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaveLoadout
{
    /** @param array{name: string, description?: ?string, character_id: int, throwable_id?: ?int, mods: array<int, int>, overclocks: array<int, int>, equipment_mods: array<int, int>} $data */
    public function handle(array $data, ?User $user, ?Loadout $loadout = null): Loadout
    {
        return DB::transaction(function () use ($data, $user, $loadout): Loadout {
            $isNew = $loadout === null;
            $loadout ??= new Loadout;
            $loadout->fill(Arr::only($data, ['name', 'description', 'character_id', 'throwable_id']));
            if ($isNew) {
                $loadout->user_id = $user?->id;
            }
            $loadout->save();
            $loadout->mods()->sync($data['mods']);
            $loadout->overclocks()->sync($data['overclocks']);
            $loadout->equipment_mods()->sync($data['equipment_mods']);
            if ($isNew && $user) {
                $user->upVote($loadout);
            }

            return $loadout;
        });
    }
}

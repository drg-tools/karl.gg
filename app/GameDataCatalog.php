<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class GameDataCatalog
{
    /** @return array<string, mixed> */
    public function data(bool $seasonSeven = false): array
    {
        $file = $seasonSeven ? 'game_data_season_7.json' : 'game_data.json';

        return json_decode(file_get_contents(database_path('seeders/'.$file)), true, flags: JSON_THROW_ON_ERROR);
    }

    /** @return list<array{record: string, action: string, changes: array<string, array{before: mixed, after: mixed}>}> */
    public function sync(bool $apply = false, ?string $weaponName = null, bool $seasonSeven = false): array
    {
        $weapons = $this->data($seasonSeven)['weapons'];
        if ($weaponName !== null) {
            $weapons = array_values(array_filter($weapons, fn (array $weapon): bool => $weapon['name'] === $weaponName));
            if ($weapons === []) {
                throw new RuntimeException("Unknown curated weapon: {$weaponName}.");
            }
        }

        return DB::transaction(function () use ($apply, $weapons, $seasonSeven): array {
            $changes = [];
            foreach ($weapons as $weapon) {
                $characters = Character::where('name', $weapon['character'])->when($apply, fn ($query) => $query->lockForUpdate())->get();
                if ($characters->count() !== 1) {
                    throw new RuntimeException("Expected exactly one {$weapon['character']} character.");
                }
                $character = $characters->first();
                $guns = Gun::where('name', $weapon['name'])->get();
                if ($guns->count() > 1) {
                    throw new RuntimeException("Duplicate weapon: {$weapon['name']}.");
                }
                $gun = $guns->first() ?? new Gun;
                if ($seasonSeven && ! $gun->exists) {
                    throw new RuntimeException("Season 7 requires an existing weapon: {$weapon['name']}.");
                }
                if ($gun->exists && ($gun->character_id !== $character->id || $gun->character_slot !== $weapon['character_slot'])) {
                    throw new RuntimeException("Weapon ownership conflict: {$weapon['name']}.");
                }
                $attributes = array_diff_key($weapon, array_flip(['character', 'mods', 'overclocks']));
                $attributes['character_id'] = $character->id;
                $this->stage($gun, $attributes, $weapon['name'], $apply, $changes);

                foreach (['mods' => Mod::class, 'overclocks' => Overclock::class] as $relation => $modelClass) {
                    foreach ($weapon[$relation] as $entry) {
                        $identity = $relation === 'mods'
                            ? ['mod_tier' => $entry['mod_tier'], 'mod_index' => $entry['mod_index']]
                            : ['overclock_index' => $entry['overclock_index']];
                        $nameKey = $relation === 'mods' ? 'mod_name' : 'overclock_name';
                        $matches = $gun->exists ? $modelClass::where('gun_id', $gun->id)->where($identity)->get() : collect();
                        if ($matches->count() > 1 || ($matches->isNotEmpty() && $matches->first()->{$nameKey} !== $entry[$nameKey])) {
                            throw new RuntimeException("Slot conflict: {$weapon['name']} / {$entry[$nameKey]}.");
                        }
                        if ($gun->exists && $modelClass::where('gun_id', $gun->id)->where($nameKey, $entry[$nameKey])->when($relation === 'mods', fn ($query) => $query->where('mod_tier', $entry['mod_tier']))->when($matches->isNotEmpty(), fn ($query) => $query->where('id', '!=', $matches->first()->id))->exists()) {
                            throw new RuntimeException("Name exists in another slot: {$entry[$nameKey]}.");
                        }
                        $record = $matches->first() ?? new $modelClass;
                        if ($record->exists && $record->character_id !== $character->id) {
                            throw new RuntimeException("Upgrade ownership conflict: {$entry[$nameKey]}.");
                        }
                        $attributes = array_diff_key($entry, ['remove_stats' => true]);
                        if ($seasonSeven && ! $record->exists && $relation === 'overclocks') {
                            $attributes += array_fill_keys([
                                'credits_cost', 'magnite_cost', 'bismor_cost', 'umanite_cost',
                                'croppa_cost', 'enor_pearl_cost', 'jadiz_cost',
                            ], 0);
                        }
                        $this->stage($record, [...$attributes, 'gun_id' => $gun->id, 'character_id' => $character->id], $weapon['name'].' / '.$entry[$nameKey], $apply, $changes, $entry['remove_stats'] ?? []);
                    }
                }
            }

            return $changes;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<array{record: string, action: string, changes: array<string, array{before: mixed, after: mixed}>}>  $changes
     * @param  array<string, array<string, mixed>>  $removeStats
     */
    private function stage(Model $record, array $attributes, string $label, bool $apply, array &$changes, array $removeStats = []): void
    {
        $differences = [];
        foreach ($attributes as $key => $value) {
            $before = $record->getAttribute($key);
            if ($key === 'json_stats') {
                $before = $before ? json_decode($before, true, flags: JSON_THROW_ON_ERROR) : [];
                if (! is_array($before)) {
                    throw new RuntimeException("Statistics must be an object: {$label}.");
                }
                if (! $record instanceof Gun && array_diff_key($before, $value, $removeStats) !== []) {
                    throw new RuntimeException("Unreviewed upgrade statistics: {$label}.");
                }
                foreach ($removeStats as $statKey => $expected) {
                    if (array_key_exists($statKey, $before)) {
                        if ($before[$statKey] != $expected) {
                            throw new RuntimeException("Removed statistic has changed: {$label} / {$statKey}.");
                        }
                        $differences['json_stats.'.$statKey] = ['before' => $before[$statKey], 'after' => null];
                        unset($before[$statKey]);
                    }
                }
                foreach ($value as $statKey => $stat) {
                    $previous = $before[$statKey] ?? null;
                    if ($previous !== null && (! is_array($previous) || ! isset($previous['name']))) {
                        throw new RuntimeException("Invalid statistic: {$label} / {$statKey}.");
                    }
                    if ($previous && $previous['name'] !== $stat['name'] && ! ($statKey === 'ex12' && $previous['name'] === 'Endothermic Explosion' && $stat['name'] === 'Plastcrete Catalyst')) {
                        throw new RuntimeException("Stat key conflict: {$label} / {$statKey}.");
                    }
                    if ($previous != $stat) {
                        $differences['json_stats.'.$statKey] = ['before' => $previous, 'after' => $stat];
                    }
                }
                $record->setAttribute($key, json_encode(array_replace($before, $value), JSON_THROW_ON_ERROR));
            } else {
                if ($before != $value) {
                    $differences[$key] = ['before' => $before, 'after' => $value];
                }
                $record->setAttribute($key, $value);
            }
        }
        if (! $record->exists || $differences !== []) {
            $changes[] = ['record' => $label, 'action' => $record->exists ? 'update' : 'create', 'changes' => $differences];
            if ($apply) {
                $record->save();
            }
        }
    }
}

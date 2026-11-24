<?php

namespace App;

use App\Models\Character;
use App\Models\Gun;
use App\Models\Mod;
use App\Models\Overclock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class GameDataCatalog
{
    /** @return array<string, mixed> */
    public function data(?string $file = null): array
    {
        $file ??= database_path('seeders/game_data.json');
        if (! is_file($file) || ! is_readable($file)) {
            throw new RuntimeException("Cannot read catalog file: {$file}.");
        }

        $data = json_decode(file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($data['weapons'] ?? null) || ! array_is_list($data['weapons'])) {
            throw new RuntimeException("Catalog must contain a list of weapons: {$file}.");
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>|null  $data
     * @return list<array{record: string, action: string, changes: array<string, array{before: mixed, after: mixed}>}>
     */
    public function sync(bool $apply = false, ?string $weaponName = null, ?array $data = null): array
    {
        $data ??= $this->data();
        $weapons = $data['weapons'];
        $requiresExistingWeapons = $data['requires_existing_weapons'] ?? false;
        $newOverclockCosts = $data['new_overclock_costs'] ?? [];
        if ($weaponName !== null) {
            $weapons = array_values(array_filter($weapons, fn (array $weapon): bool => $weapon['name'] === $weaponName));
            if ($weapons === []) {
                throw new RuntimeException("Unknown curated weapon: {$weaponName}.");
            }
        }

        return DB::transaction(function () use ($apply, $weapons, $requiresExistingWeapons, $newOverclockCosts): array {
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
                if ($requiresExistingWeapons && ! $gun->exists) {
                    throw new RuntimeException("Catalog requires an existing weapon: {$weapon['name']}.");
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
                        if (! $record->exists && $relation === 'overclocks') {
                            $attributes += $newOverclockCosts;
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

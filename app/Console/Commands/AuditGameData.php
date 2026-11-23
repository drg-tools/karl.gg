<?php

namespace App\Console\Commands;

use App\GameDataCatalog;
use App\Models\BuildMetric;
use App\Models\Gun;
use App\Models\Patch;
use App\Models\Throwable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use JsonException;
use RuntimeException;

#[Signature('app:audit-game-data')]
#[Description('Report weapon inventory, data integrity, metric coverage and curated differences without writing data')]
class AuditGameData extends Command
{
    public function handle(GameDataCatalog $catalog): int
    {
        $guns = Gun::with(['character', 'mods', 'overclocks'])->orderBy('id')->get();
        $issues = [];
        foreach ($guns->groupBy('name') as $name => $entries) {
            if ($entries->count() > 1) {
                $issues[] = "Duplicate weapon: {$name}";
            }
        }
        foreach ($guns as $gun) {
            if (! $gun->character) {
                $issues[] = "{$gun->name}: missing character";
            }
            foreach ([$gun, ...$gun->mods, ...$gun->overclocks] as $record) {
                try {
                    $stats = json_decode($record->json_stats, true, flags: JSON_THROW_ON_ERROR);
                    if (! is_array($stats)) {
                        $issues[] = class_basename($record).' #'.$record->id.': statistics must be an object';

                        continue;
                    }
                    foreach ($stats as $key => $stat) {
                        if (! is_array($stat) || ! isset($stat['name']) || ! array_key_exists('value', $stat)) {
                            $issues[] = class_basename($record).' #'.$record->id.": invalid stat {$key}";
                        }
                    }
                } catch (JsonException) {
                    $issues[] = class_basename($record).' #'.$record->id.': invalid statistics JSON';
                }
            }
            foreach (range(1, 5) as $tier) {
                if (! $gun->mods->contains('mod_tier', $tier)) {
                    $issues[] = "{$gun->name}: missing tier {$tier}";
                }
            }
            foreach (['mods', 'overclocks'] as $relation) {
                foreach ($gun->{$relation} as $record) {
                    if ($record->character_id !== $gun->character_id) {
                        $issues[] = class_basename($record).' #'.$record->id.': character differs from weapon';
                    }
                }
                $slots = $gun->{$relation}->groupBy(fn ($record) => $relation === 'mods' ? $record->mod_tier.$record->mod_index : $record->overclock_index);
                foreach ($slots as $slot => $entries) {
                    if ($entries->count() > 1) {
                        $issues[] = "{$gun->name}: duplicate {$relation} slot {$slot}";
                    }
                }
            }
        }
        $reference = json_decode(file_get_contents(database_path('seeders/game_inventory.json')), true, flags: JSON_THROW_ON_ERROR);
        $normalize = fn (string $name): string => preg_replace('/[^a-z0-9]/', '', strtolower($name));
        $present = $guns->map(fn (Gun $gun): string => $normalize($gun->name))->all();
        $missing = array_values(array_filter($reference['weapons'], fn (string $name): bool => ! in_array($normalize($name), $present, true)));
        $expected = array_map($normalize, $reference['weapons']);
        $curated = array_column($catalog->data()['weapons'], 'name');
        try {
            $changes = $catalog->sync();
        } catch (RuntimeException|JsonException $exception) {
            $changes = [];
            $issues[] = 'Curated comparison blocked: '.$exception->getMessage();
        }
        $metricGunIds = BuildMetric::query()->distinct()->pluck('gun_id');
        $this->line(json_encode([
            'reference' => $reference,
            'latest_database_patch' => Patch::orderByDesc('launched_at')->first()?->only(['patch_title', 'patch_num', 'short_name', 'launched_at']),
            'inventory' => $guns->map(fn (Gun $gun): array => [
                'id' => $gun->id, 'name' => $gun->name, 'character' => $gun->character?->name,
                'mods' => $gun->mods->count(), 'overclocks' => $gun->overclocks->count(),
            ])->all(),
            'throwables' => Throwable::orderBy('id')->pluck('name')->all(),
            'missing_weapons' => $missing,
            'unexpected_weapons' => $guns->filter(fn (Gun $gun): bool => ! in_array($normalize($gun->name), $expected, true))->pluck('name')->all(),
            'integrity_issues' => $issues,
            'numerical_review_required' => $guns->whereNotIn('name', $curated)->pluck('name')->all(),
            'curated_reference' => array_diff_key($catalog->data(), ['weapons' => true]),
            'review_scope' => 'Reference-table coverage only. Follow-up items in curated_reference.reviews are not certified or silently resolved.',
            'curated_changes' => $changes,
            'advanced_metric_coverage' => BuildMetric::query()->select('gun_id', 'patch_id')->selectRaw('count(*) as combinations')->groupBy('gun_id', 'patch_id')->orderBy('gun_id')->orderBy('patch_id')->get()->toArray(),
            'advanced_metric_patches' => Patch::whereIn('id', BuildMetric::query()->select('patch_id')->distinct())->orderBy('id')->get(['id', 'patch_title', 'patch_num', 'short_name', 'launched_at'])->toArray(),
            'weapons_without_advanced_metrics' => $guns->whereNotIn('id', $metricGunIds)->pluck('name')->all(),
            'metric_status' => 'Coverage only; stored metrics require separate source and patch verification.',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

        return self::SUCCESS;
    }
}

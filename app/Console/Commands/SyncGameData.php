<?php

namespace App\Console\Commands;

use App\GameDataCatalog;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use JsonException;
use RuntimeException;

#[Signature('app:sync-game-data {file? : Path to a JSON catalog; defaults to the curated catalog} {--apply : Save changes; default is a read-only preview} {--weapon= : Limit to one exact weapon name}')]
#[Description('Preview or apply the curated weapon catalog, preserving existing IDs and loadout links')]
class SyncGameData extends Command
{
    public function handle(GameDataCatalog $catalog): int
    {
        try {
            $data = $catalog->data($this->argument('file'));
            $changes = $catalog->sync((bool) $this->option('apply'), $this->option('weapon'), $data);
        } catch (RuntimeException|JsonException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->line(json_encode(['mode' => $this->option('apply') ? 'applied' : 'preview', 'changes' => $changes, 'limitations' => $data['limitations'] ?? []], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

        return self::SUCCESS;
    }
}

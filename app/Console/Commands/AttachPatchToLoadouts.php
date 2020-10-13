<?php

namespace App\Console\Commands;

use App\Loadout;
use App\Patch;
use Illuminate\Console\Command;

class AttachPatchToLoadouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:attach-loaodut-patches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures that all loadouts have the proper patch attached based on date information';
    /**
     * @var Patch
     */
    private $patch;

    /**
     * @var Loadout
     */
    private $loadout;

    /**
     * Create a new command instance.
     *
     * @param  Patch  $patch
     * @param  Loadout  $loadout
     */
    public function __construct(Patch $patch, Loadout $loadout)
    {
        parent::__construct();
        $this->patch = $patch;
        $this->loadout = $loadout;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loadouts = $this->loadout->all();
        $patches = $this->patch->latest('launched_at')->get();

        foreach ($loadouts as $loadout) {
            $patch = $patches->firstWhere('launched_at', '<', $loadout->updated_at);

            if ($patch && $loadout->patch_id !== $patch->id) {
                $this->info("Updating {$loadout->name} from {$loadout->patch_id} to {$patch->id}");
                $loadout->timestamps = false;
                $loadout->patch_id = $patch->id;

                // Don't run model events as this will grab the latest patch before saving (the default behavior)
                $loadout->saveQuietly();
            }
        }
    }
}

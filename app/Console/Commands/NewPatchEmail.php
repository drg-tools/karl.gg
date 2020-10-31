<?php

namespace App\Console\Commands;

use App\Loadout;
use App\Mail\LoadoutOutdated;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NewPatchEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-patch-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gather users with old loadouts and send email to update them.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Grab all loadouts that are older than current patch
        $oldLoadouts = Loadout::onOlderPatch()->get();

        // Group by user_id
        $groupedByUser = $oldLoadouts->groupBy('user_id');
        $this->info("Found {$groupedByUser->count()} users to email...");

        // Send email with all loadout links and instructions
        $bar = $this->output->createProgressBar($groupedByUser->count());
        foreach ($groupedByUser as $userId => $loadouts) {
            $user = User::find($userId);

            if ($user) {
                Mail::to($user)->send(new LoadoutOutdated($loadouts));
            }
            $bar->advance();
        }
    }
}

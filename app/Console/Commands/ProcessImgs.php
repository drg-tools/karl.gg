<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProcessImgs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-imgs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take imgs in tmp location and process them.';

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
     * @return mixed
     */
    public function handle()
    {
        $dirs = File::directories('public/img/mods/tmp');

        foreach ($dirs as $dir) {
            $this->info("Processing {$dir}...");

            // Get all tmp files
            $files = File::allFiles($dir);

            // Loop
            foreach ($files as $file) {

                // Remove all but "Upgrade"
                if (!Str::contains($file->getFilename(), "Icon_Upgrade")) {

                    File::delete($file->getPathname());
                } else {
                    $newName = Str::of($file->getFilename())
                        ->replaceMatches('/.+px-Icon_/', '')
                        ->replaceMatches('/0?\.png/', '')
                        ->slug('_')
                        ->lower();

                    File::move($file->getPathname(), "public/img/mods/{$newName}.png");
                }
            }
        }

        // Normalize name

    }
}

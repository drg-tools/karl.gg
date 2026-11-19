<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Short names for the patches that already exist, keyed by the patch_num_dev column this migration drops.
     *
     * @var array<string, string>
     */
    private const KNOWN_SHORT_NAMES = [
        '1.40' => 'Season 6',
        '1.39.101466.0' => 'Season 5',
        '1.38.10' => 'S4 Maintenance',
        '1.38' => 'Season 4',
        '1.37' => 'Season 3',
        '1.36' => 'Season 2',
        '1.35' => 'Season 1',
        '1.34' => 'Update 34',
        '1.33' => 'Update 33',
        '1.32.45559.0' => 'Update 32',
        '1.31.41035.0' => 'Update 31',
        '1.0.0' => '1.0 Release',
        'v0.29.0' => 'Update 29',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patches', function (Blueprint $table) {
            $table->string('short_name', 30)->nullable()->after('patch_title');
        });

        foreach (self::KNOWN_SHORT_NAMES as $patchNumDev => $shortName) {
            DB::table('patches')
                ->where('patch_num_dev', $patchNumDev)
                ->whereNull('short_name')
                ->update(['short_name' => $shortName]);
        }

        Schema::table('patches', function (Blueprint $table) {
            $table->dropColumn('patch_num_dev');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patches', function (Blueprint $table) {
            $table->string('patch_num_dev', 20)->nullable()->after('patch_num');
        });

        foreach (self::KNOWN_SHORT_NAMES as $patchNumDev => $shortName) {
            DB::table('patches')
                ->where('short_name', $shortName)
                ->update(['patch_num_dev' => $patchNumDev]);
        }

        Schema::table('patches', function (Blueprint $table) {
            $table->dropColumn('short_name');
        });
    }
};

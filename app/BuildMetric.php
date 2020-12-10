<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

/**
 * App\BuildMetric
 *
 * @property int $id
 * @property int $character_id
 * @property int $gun_id
 * @property string $weapon_short_name
 * @property string $build_combination
 * @property float $ideal_burst_dps
 * @property float $burst_dps_wp
 * @property float $burst_dps_acc
 * @property float $burst_dps_aw
 * @property float $burst_dps_wp_acc
 * @property float $burst_dps_wp_aw
 * @property float $burst_dps_acc_aw
 * @property float $burst_dps_wp_acc_aw
 * @property float $ideal_sustained_dps
 * @property float $sustained_dps_wp
 * @property float $sustained_dps_acc
 * @property float $sustained_dps_aw
 * @property float $sustained_dps_wp_acc
 * @property float $sustained_dps_wp_aw
 * @property float $sustained_dps_acc_aw
 * @property float $sustained_dps_wp_acc_aw
 * @property float $ideal_additional_target_dps
 * @property float $max_num_targets_per_shot
 * @property float $max_multi_target_damage
 * @property float $ammo_efficiency
 * @property float $damage_wasted_by_armor
 * @property float $general_accuracy
 * @property float $weakpoint_accuracy
 * @property float $firing_duration
 * @property float $average_time_to_kill
 * @property float $average_overkill
 * @property float $breakpoints
 * @property float $utility
 * @property float $average_time_to_ignite_or_freeze
 * @property float $damage_per_magazine
 * @property float $time_to_fire_magazine
 * @property int $patch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Character $character
 * @property-read \App\Gun $gun
 * @property-read \App\Patch $patch
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric query()
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereAmmoEfficiency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereAverageOverkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereAverageTimeToIgniteOrFreeze($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereAverageTimeToKill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBreakpoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBuildCombination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsAccAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsWp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsWpAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsWpAccAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereBurstDpsWpAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereDamagePerMagazine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereDamageWastedByArmor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereFiringDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereGeneralAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereGunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereIdealAdditionalTargetDps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereIdealBurstDps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereIdealSustainedDps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereMaxMultiTargetDamage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereMaxNumTargetsPerShot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric wherePatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsAccAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsWp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsWpAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsWpAccAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereSustainedDpsWpAw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereTimeToFireMagazine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereUtility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereWeakpointAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuildMetric whereWeaponShortName($value)
 * @mixin \Eloquent
 */
class BuildMetric extends Model
{
    use CrudTrait;
    protected $guarded = ['id'];

    public function gun()
    {
        return $this->belongsTo(Gun::class, 'gun_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function patch()
    {
        return $this->belongsTo(Patch::class);
    }

    public function getModMatrix($gun, $combo)
    {
        // Object to determine if we have a selected mod in a tier
        $selected_index = [];

        // Mods for our gun, nicely grouped into their proper tiers.
        // It just happens to be in the right order, so we don't need to do any further transformation
        // We will cross-index this with out selected_index
        $gun_mods = $gun->mods->groupBy('mod_tier');

        // Let's break our Combo String into an actual array that we can work with.
        // Allows us to check for selected items
        $combo_array = str_split($combo);

        // Iterate through the combo array to see if we have a selected item on the tier
        // Goal here is to get the mod that is selected for each tier in an object for our view
        foreach ($combo_array as $key => $tier) {
            // By default, assume the tier is empty
            $selected = false;

            // If our combo string is NOT '-' we have something selected
            if ($tier != '-') {
                $selected = true;
            }

            // Our selected_index object needs to correspond to our mod tiers
            // Take our $key which is basically just our loop iterator, and +1 to get the proper tier for our array
            // Save the value of our tier, and a quick boolean object if it's selected
            $selected_index[$key + 1] = ['value' => $tier, 'selected' => $selected];
        }

        // Compact our two arrays into one to return to the view
        // We will use both of these arrays to construct our frontend mod matrix
        // We will be able to show all the mods in the right order, plus the selected ones
        $matrixArray = compact('gun_mods', 'selected_index');

        return $matrixArray;
    }

    public function getOverclock($gun, $index)
    {
        $overclock = Overclock::where([
            ['gun_id', '=', $gun->id],
            ['overclock_index', '=', $index],
        ])->firstOrFail();

        return $overclock;
    }
}

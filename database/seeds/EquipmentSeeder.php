<?php

use App\Equipment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run()
    {
        // Seed as of 7/1/2020
        $equipment = [
            ['character_id' => 3, 'name' => 'Reinforced Power Drills', 'json_stats' =>  '{"dmg": {"name": "Damage", "value": 5 }, "ammo": {"name": "Max Fuel", "value": 38}, "rate": {"name": "Mining Rate", "value": 100, "percent": true}, "ex1": {"name": "Overheat Duration", "value": 8}, "ex2": {"name": "Cooling Rate", "value": 2}, "ex3": {"name": "Movement speed while drilling", "value": 0, "percent": true}, "ex4": {"name": "Heat removal on Damage", "value": 0}}', 'icon' => 'D_E_Drill', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 3, 'name' => 'Satchel Charge', 'json_stats' => '{"dmg": {"name": "Area Damage", "value": 375}, "radius": {"name": "Damage Radius", "value": 4.5}, "ammo": {"name": "Carried Amount", "value": 2}, "diameter": {"name": "Carve Diameter", "value": 6.2}, "ex1": {"name": "Extra Fear Radius", "value": 0}, "ex2": {"name": "Extra Stagger Radius", "value": 0}, "ex3": {"name": "Can be picked up", "value": 0, "boolean": true }, "ex4": {"name": "Unstable explosives", "value": 0, "boolean": true}}', 'icon' =>  'D_E_Satchel', 'eq_type' => 'High Explosive', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 1, 'name' => 'Platform Gun', 'json_stats' => '{"clip": {"name": "Clip Size", "value": 4}, "ammo": {"name": "Max Ammo", "value": 16}, "rate": {"name": "Rate of Fire", "value": 1}, "reload": {"name": "Reload Time", "value": 2}, "ex1": {"name": "Less fall damage", "value": 0, "boolean": true}, "ex2": {"name": "Bug repellent", "value": 0, "boolean": true}}', 'icon' => 'E_E_Platform', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 1, 'name' => 'LMG Gun Platform', 'json_stats' => '{"ammo": {"name": "Carried Amount", "value": 425}, "rate": {"name": "Construction Time", "value": 4}, "clip": {"name": "Sentry Ammo Capacity", "value": 90}, "reload": {"name": "Reload Ammo Per Second", "value": 45}, "dmg": {"name": "Damage", "value": 5}, "rof": {"name": "Rate of Fire", "value": 7.5}, "range": {"name": "Max Targeting Range", "value": 20}, "amount": {"name": "Number of Sentries", "value": 1}, "ex1": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex2": {"name": "Stun Chance", "value": 0, "percent": true}, "ex3": {"name": "Defender System", "value": 0, "boolean": true}, "ex4": {"name": "Hawkeye System", "value": 0, "boolean": true}}', 'icon' =>  'E_E_Sentry', 'eq_type' => 'Sentry Gun', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 4, 'name' => 'Zipline Launcher', 'json_stats' => '{"range": {"name": "Max Range", "value": 30}, "ammo": {"name": "Max Ammo", "value": 3}, "reload": {"name": "Reload Time", "value": 1.5}, "angle": {"name": "Max Angle", "value": 30}, "speed": {"name": "Movement Speed", "value": 250}, "ex1": {"name": "Fall Damage Reduction", "value": 0, "boolean": true}}', 'icon' =>  'G_E_Zipline', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 4, 'name' => 'Shield Generator', 'json_stats' => '{"ammo": {"name": "Carried Amount", "value": 1}, "radius": {"name": "Shield Radius", "value": 2.7}, "duration": {"name": "Duration", "value": 6}, "rate": {"name": "Shield Regen per second", "value": 10}, "reload": {"name": "Recharge Time", "value": 12.5}, "ex1": {"name": "Protection Time After Leaving", "value": 0, "boolean": true}}', 'icon' =>  'G_E_Shield', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 2, 'name' => 'Grappling Hook', 'json_stats' => '{"range": {"name": "Max Range", "value": 20}, "cool": {"name": "Cooldown", "value": 4}, "speed": {"name": "Max Speed", "value": 1500}, "wind": {"name": "Wind up time", "value": 0.4}, "risk": {"name": "Risk of Accidental Death", "value": "HIGH"}, "ex1": {"name": "Fall damage reduction after release", "value": 0, "boolean": true}, "ex2": {"name": "Faster movement after release", "value": 0, "boolean": true}}', 'icon' =>  'S_E_Grapling', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 2, 'name' => 'Flare Gun', 'json_stats' => '{"dmg": {"name": "Damage", "value": 40}, "duration": {"name": "Duration", "value": 75}, "clip": {"name": "Magazine Size", "value": 3}, "ammo": {"name": "Max Ammo", "value": 12}, "reload": {"name": "Reload Time", "value": 2.4}, "rate": {"name": "Rate of Fire", "value": 1}, "ex1": {"name": "Auto Reload", "value": 0, "boolean": true}}', 'icon' =>  'S_E_Flare', 'eq_type' => 'Support Tool', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 1, 'name' => 'Engineering Suit', 'json_stats' => '{ "shield": { "name": "Shield", "value": 25 }, "health": { "name": "Health", "value": 110 }, "poison": { "name": "Poison Resistance", "value": 0, "percent": true }, "delay": { "name": "Regeneration Delay", "value": 7 }, "rate": { "name": "Regeneration Rate", "value": 100, "percent": true }, "revive": { "name": "Revive Invulnerability", "value": 3 }, "carry": { "name": "Carry Capacity", "value": 40 }, "ex1": { "name": "AoE Damage On Shield Break", "value": 0, "boolean": true }, "ex2": { "name": "AoE Stun On Shield Break", "value": 0, "boolean": true } }', 'icon' =>  'X_E_Armor', 'eq_type' => 'Armor', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 2, 'name' => 'Light Scouting Suit', 'json_stats' => '{ "shield": { "name": "Shield", "value": 25 }, "health": { "name": "Health", "value": 110 }, "fall": { "name": "Fall Damage Reduction", "value": 0, "percent": true }, "delay": { "name": "Regeneration Delay", "value": 7 }, "rate": { "name": "Regeneration Rate", "value": 100, "percent": true }, "revive": { "name": "Revive Invulnerability", "value": 3 }, "carry": { "name": "Carry Capacity", "value": 40 }, "ex1": { "name": "AoE Damage On Shield Break", "value": 0, "boolean": true }, "ex2": { "name": "AoE Stun On Shield Break", "value": 0, "boolean": true } }', 'icon' =>  'X_E_Armor', 'eq_type' => 'Armor', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 3, 'name' => 'Heavy Drill Suit', 'json_stats' => '{ "shield": { "name": "Shield", "value": 25 }, "health": { "name": "Health", "value": 110 }, "fire": { "name": "Fire Resistance", "value": 0, "percent": true }, "delay": { "name": "Regeneration Delay", "value": 7 }, "rate": { "name": "Regeneration Rate", "value": 100, "percent": true }, "revive": { "name": "Revive Invulnerability", "value": 3 }, "carry": { "name": "Carry Capacity", "value": 40 }, "ex1": { "name": "AoE Damage On Shield Break", "value": 0, "boolean": true }, "ex2": { "name": "AoE Stun On Shield Break", "value": 0, "boolean": true } }', 'icon' =>  'X_E_Armor', 'eq_type' => 'Armor', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['character_id' => 4, 'name' => 'Reinforced Impact Suit', 'json_stats' => '{ "shield": { "name": "Shield", "value": 25 }, "health": { "name": "Health", "value": 110 }, "explosion": { "name": "Explosion Resistance", "value": 0, "percent": true }, "delay": { "name": "Regeneration Delay", "value": 7 }, "rate": { "name": "Regeneration Rate", "value": 100, "percent": true }, "revive": { "name": "Revive Invulnerability", "value": 3 }, "carry": { "name": "Carry Capacity", "value": 40 }, "ex1": { "name": "AoE Damage On Shield Break", "value": 0, "boolean": true }, "ex2": { "name": "AoE Stun On Shield Break", "value": 0, "boolean": true } }', 'icon' =>  'X_E_Armor', 'eq_type' => 'Armor', 'patch_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ];
        Equipment::insert($equipment);
    }
}

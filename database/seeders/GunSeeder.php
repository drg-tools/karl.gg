<?php

namespace Database\Seeders;

use App\Gun;
use Illuminate\Database\Seeder;

class GunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 1    Engineer
         * 2    Scout
         * 3    Driller
         * 4    Gunner.
         */
        $guns = [
            [
                'name' => '"Warthog" Auto 210', 'character_id' => 1, 'image' => 'E_P1_Warthog',
                'gun_class' => 'Shotgun', 'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 7}, "ammo": {"name": "Max Ammo", "value": 90}, "clip": {"name": "Magazine Size", "value": 6}, "rate": {"name": "Rate of Fire", "value": 2}, "reload": {"name": "Reload Time", "value": 2}, "ex1": {"name": "Pellets", "value": 8}, "ex2": {"name": "Weakpoint Stun Duration", "value": 3}, "ex3": {"name": "Weakpoint Stun Chance Per Pellet", "value": 10, "percent": true}, "ex9": {"name": "Weakpoint Damage Bonus", "value": 0, "percent": true}, "ex4": {"name": "Recoil", "value": 100, "percent": true}, "ex5": {"name": "Base Spread", "value": 100, "percent": true } , "ex6": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex7": {"name": "Turret Whip", "value": 0, "boolean": true}, "ex8": {"name": "Miner Adjustments", "value": 0, "boolean": true}, "ex10": {"name": "Stun Chance on all body parts", "value": 0, "boolean": true}, "ex11": {"name": "Bonus Damage Vs Stunned", "value": 0, "boolean": true}}',
            ],
            [
                'name' => '"Stubby" Voltaic SMG', 'character_id' => 1, 'image' => 'E_P2_Stubby',
                'gun_class' => 'Submachine Gun', 'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 9}, "ammo": {"name": "Max Ammo", "value": 420}, "clip": {"name": "Magazine Size", "value": 30}, "rate": {"name": "Rate of Fire", "value": 11}, "reload": {"name": "Reload Time", "value": 2}, "spread": {"name": "Base Spread", "value": 100, "percent": true}, "ex1": {"name": "Electric Damage", "value": 0}, "ex2": {"name": "Electrocution %", "value": 20, "percent": true}, "ex3": {"name": "Recoil", "value": 100, "percent": true}, "ex4": {"name": "Weakpoint Damage Bonus", "value": 0, "percent": true}, "ex5": {"name": "Damage vs Electrically Affected", "value": 0, "percent": true}, "ex6": {"name": "Electrocution AoE", "value": 0, "percent": true}, "ex7": {"name": "Turret Arc (10m range)", "value": 0, "boolean": true}, "ex8": {"name": "Turret EM Discharge (5m range)", "value": 0, "boolean": true}}',
            ],
            [
                'name' => 'Deepcore 40MM PGL', 'character_id' => 1, 'image' => 'E_S1_PGL',
                'gun_class' => 'Heavy Weapon', 'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Area Damage", "value": 110}, "ammo": {"name": "Max Ammo", "value": 8}, "clip": {"name": "Clip Size", "value": 1}, "rate": {"name": "Rate of Fire", "value": 2}, "reload": {"name": "Reload Time", "value": 2}, "ex1": {"name": "Effect Radius", "value": 2.5}, "ex2": {"name": "Armor Breaking", "value": 50, "percent": true}, "ex3": {"name": "Fear Factor", "value": 100, "percent": true}, "ex4": {"name": "Projectile Velocity", "value": 100, "percent": true}, "ex5": {"name": "% Converted to Fire", "value": 0, "percent": true}, "ex6": {"name": "Stun Chance", "value": 0, "percent": true}, "ex7": {"name": "Proximity Trigger", "value": 0, "boolean": true}, "ex8": {"name": "Direct Damage", "value": 0}, "ex10": {"name": "RJ250 Compound", "value": 0, "boolean": true}}',
            ],
            [
                'name' => 'Breach Cutter', 'character_id' => 1, 'image' => 'E_S2_Breach',
                'gun_class' => 'Heavy Weapon', 'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Beam DPS", "value": 575}, "ammo": {"name": "Max Ammo", "value": 12}, "clip": {"name": "Magazine Size", "value": 4}, "rate": {"name": "Rate of Fire", "value": 1.5}, "reload": {"name": "Reload Time", "value": 3}, "ex1": {"name": "Projectile Lifetime", "value": 1.5}, "ex2": {"name": "Plasma Beam Width", "value": 2}, "ex3": {"name": "Plasma Expansion Delay", "value": 0.2}, "ex4": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex5": {"name": "Explosive Goodbye", "value": 0, "boolean": true}, "ex6": {"name": "Plasma Trail", "value": 0, "boolean": true}, "ex7": {"name": "Triple Split Line", "value": 0, "boolean": true}, "ex8": {"name": "Roll Control", "value": 0, "boolean": true}, "ex9": {"name": "Return to Sender", "value": 0, "boolean": true}, "ex10": {"name": "Spinning Death", "value": 0, "boolean": true}, "ex11": {"name": "High Voltage Crossover", "value": 0, "boolean": true}, "ex12": {"name": "Inferno", "value": 0, "boolean": true}, "ex13": {"name": "Stun Chance", "value": 0, "percent": true}, "ex14": {"name": "Stun Duration", "value": 0}}',
            ],
            [
                'name' => 'Deepcore GK2', 'character_id' => 2, 'image' => 'S_P1_GK2', 'gun_class' => 'Assault Rifle',
                'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 15}, "ammo": {"name": "Max Ammo", "value": 350}, "clip": {"name": "Magazine Size", "value": 25}, "rate": {"name": "Rate of Fire", "value": 7}, "reload": {"name": "Reload Time", "value": 1.8}, "ex1": {"name": "Weakpoint Stun Duration", "value": 1.5}, "ex2": {"name": "Weakpoint Stun Chance", "value": 10, "percent": true}, "ex3": {"name": "Base Spread", "value": 100, "percent": true}, "ex4": {"name": "Recoil", "value": 100, "percent": true}, "ex5": {"name": "Weakpoint Damage Bonus", "value": 10, "percent": true}, "ex6": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex7": {"name": "Battle Frenzy", "value": 0, "boolean": true}, "ex8": {"name": "Battle Cool", "value": 0, "boolean": true}, "ex9": {"name": "Bonus Damage to Afflicted Targets", "value": 0, "percent": true}, "ex10": {"name": "Spread Recovery Speed", "value": 100, "percent": true}, "ex11": {"name": "Electric Reload (100% chance)", "value": 0, "boolean": true}}',
            ],
            [
                'name' => 'M1000 Classic', 'character_id' => 2, 'image' => 'S_P2_M1000',
                'gun_class' => 'Semi-Automatic Rifle',
                'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 50}, "ammo": {"name": "Max Ammo", "value": 96}, "clip": {"name": "Clip Size", "value": 8}, "rate": {"name": "Rate of Fire", "value": 4}, "reload": {"name": "Reload Time", "value": 2.5}, "ex1": {"name": "Focus Speed", "value": 100, "percent": true}, "ex2": {"name": "Focused Shot Damage Bonus", "value": 100, "percent": true}, "ex3": {"name": "Recoil", "value": 100, "percent": true}, "ex4": {"name": "Max Penetrations", "value": 0}, "ex5": {"name": "Weakpoint Damage Bonus", "value": 10, "percent": true}, "ex6": {"name": "Armor Breaking", "value": 30, "percent": true}, "ex7": {"name": "Focused Shot Stun Chance", "value": 0, "percent": true}, "ex9": {"name": "Focus Shot Fear", "value": 0, "boolean": true}, "ex8": {"name": "Focus Mode Movement Speed", "value": 30, "percent": true}, "ex10": {"name": "Focus Shot Kill AoE Fear", "value": 0, "boolean": true},  "ex11": {"name": "Focus Shot Hover", "value": 0, "boolean": true}, "ex12": {"name": "Electrocuting Focus Shots", "value": 0, "boolean": true}}',
            ],
            [
                'name' => 'Jury-Rigged Boomstick', 'character_id' => 2, 'image' => 'S_S1_Jury',
                'gun_class' => 'Shotgun',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 12}, "ammo": {"name": "Max Ammo", "value": 24}, "clip": {"name": "Magazine Size", "value": 2}, "rate": {"name": "Rate of Fire", "value": 1.5}, "reload": {"name": "Reload Time", "value": 2}, "ex1": {"name": "Pellets", "value": 8}, "ex5": {"name": "Front AoE shock wave Damage", "value": 20}, "ex2": {"name": "Stun Chance", "value": 30, "percent": true}, "ex9": {"name": "Stun Duration", "value": 2.5}, "ex3": {"name": "Max Penetrations", "value": 0}, "ex4": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex6": {"name": "Auto Reload", "value": 0, "boolean": true}, "ex7": {"name": "Proximity Fear Chance", "value": 0, "percent": true},  "ex8": {"name": "Damage % as Fire", "value": 0, "percent": true}, "ex10": {"name": "Double Barrel", "value": 0, "boolean": true}, "ex11": {"name": "Shotgun Jump", "value": 0, "boolean": true}, "ex12": {"name": "Base Spread", "value": 100, "percent": true}}',
            ],
            [
                'name' => 'Zhukov NUK17', 'character_id' => 2, 'image' => 'S_S2_Zhuk', 'gun_class' => 'Submachine Gun',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 11}, "ammo": {"name": "Max Ammo", "value": 600}, "clip": {"name": "Combined Clip Size", "value": 50}, "rate": {"name": "Combined Rate of Fire", "value": 30}, "reload": {"name": "Reload Time", "value": 1.8}, "ex1": {"name": "Base Spread", "value": 100, "percent": true}, "ex2": {"name": "Max Penetrations", "value": 0}, "ex4": {"name": "Weakpoint Damage Bonus", "value": 0, "percent": true}, "ex5": {"name": "Get in, get out", "value": 0, "boolean": true}, "ex6": {"name": "Damage vs Electrically Affected", "value": 0, "percent": true}, "ex7": {"name": "Cryo Minelets", "value": 0, "boolean": true}, "ex8": {"name": "Embedded Detonators", "value": 0, "boolean": true}, "ex9": {"name": "Movement Speed While Using", "value": 100, "percent": true}}',
            ],
            [
                'name' => 'CRSPR Flamethrower', 'character_id' => 3, 'image' => 'D_P1_CRSPR',
                'gun_class' => 'Heavy Weapon',
                'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 10}, "ex11": {"name": "Heat", "value": 10}, "ammo": {"name": "Max Fuel", "value": 300}, "clip": {"name": "Tank Size", "value": 50}, "rate": {"name": "Fuel Flow Rate", "value": 100, "percent": true}, "reload": {"name": "Reload Time", "value": 3}, "ex1": {"name": "Increased Sticky Flame Damage", "value": 0, "boolean": true}, "ex2": {"name": "Sticky Flame Burn", "value": 0}, "ex3": {"name": "Sticky Flame Slowdown", "value": 0}, "ex4": {"name": "Sticky Flame Duration", "value": 2}, "ex5": {"name": "Fear Factor", "value": 0, "percent": true}, "ex6": {"name": "Flame Reach", "value": 10}, "ex7": {"name": "Area Heat", "value": 0}, "ex9": {"name": "Killed Targets Explode %", "value": 0, "percent": true}, "ex10": {"name": "Movement Speed While Using", "value": 100, "percent": true}}',
            ],
            [
                'name' => 'Cryo Cannon', 'character_id' => 3, 'image' => 'D_P2_Cryo', 'gun_class' => 'Heavy Weapon',
                'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 6}, "clip": {"name": "Tank Capacity", "value": 400}, "rate": {"name": "Chargeup Time", "value": 0.5}, "reload": {"name": "Repressurization Delay", "value": 1}, "ex1": {"name": "Cold Stream Reach", "value": 10}, "ex2": {"name": "Freezing Power", "value": 8}, "ex3": {"name": "Pressure Drop Rate", "value": 100, "percent": true}, "ex4": {"name": "Pressure Gain Rate", "value": 100, "percent": true}, "ex5": {"name": "Frozen Targets can Shatter", "value": 0, "boolean": true}, "ex6": {"name": "Area Cold Damage", "value": 0, "boolean": true}, "ex7": {"name": "Flow Rate", "value": 100, "percent": true}, "ex8": {"name": "Ice Spear", "value": 0, "boolean": true}, "ex9": {"name": "Snowball", "value": 0, "boolean": true}}',
            ],
            [
                'name' => 'Subata 120', 'character_id' => 3, 'image' => 'D_S1_Subata', 'gun_class' => 'Pistol',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 12}, "ammo": {"name": "Max Ammo", "value": 160}, "clip": {"name": "Magazine Size", "value": 12}, "rate": {"name": "Rate of Fire", "value": 8}, "reload": {"name": "Reload Time", "value": 1.9}, "recoil": {"name": "Recoil", "value": 100, "percent": true}, "ex1": {"name": "Weakpoint Damage Bonus", "value": 20, "percent": true}, "ex2": {"name": "Base Spread", "value": 100, "percent": true}, "ex3": {"name": "Spread Per Shot", "value": 100, "percent": true}, "ex4": {"name": "Bonus Fire Damage to Burning Targets", "value": 0, "percent": true}, "ex5": {"name": "Damage Vs Mactera", "value": 0, "percent": true}, "ex6": {"name": "Weakpoint Chain Hit Chance", "value": 0, "percent": true}, "ex7": {"name": "Randomized Damage", "value": 0, "boolean": true}, "ex8": {"name": "Automatic Fire", "value": 0, "boolean": true}, "ex9": {"name": "Explosive Reload", "value": 0, "boolean": true},"ex10": {"name": "Stun Chance", "value": 0, "percent": true}}',
            ],
            [
                'name' => 'Experimental Plasma Charger', 'character_id' => 3, 'image' => 'D_S2_Plasma',
                'gun_class' => 'Pistol',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 20}, "clip": {"name": "Battery Capacity", "value": 120}, "rate": {"name": "Rate of Fire", "value": 8}, "reload": {"name": "Cooling Rate", "value": 100, "percent": true}, "ex1": {"name": "Charged Damage", "value": 60}, "ex2": {"name": "Charged Area Damage", "value": 60}, "ex3": {"name": "Charged Effect Radius", "value": 2}, "ex4": {"name": "Charged Shot Ammo Use", "value": 8}, "ex5": {"name": "Charge Speed", "value": 0.8}, "ex6": {"name": "Heat Buildup When Charged", "value": 100, "percent": true}, "ex7": {"name": "Normal Projectile Velocity", "value": 100, "percent": true}, "ex8": {"name": "Thin Containment Field", "value": 0, "boolean": true}, "ex9": {"name": "Flying Nightmare", "value": 0, "boolean": true}, "ex10": {"name": "No Charged Shot Insta-Overheat", "value": 0, "boolean": true}, "ex11": {"name": "Plasma Burn", "value": 0, "boolean": true}, "ex12": {"name": "Normal Shot Heat Generation", "value": 100, "percent": true}, "ex13": {"name": "Persistent Plasma", "value": 0, "boolean": true}}',
            ],
            [
                'name' => '"Lead Storm" Powered Minigun', 'character_id' => 4, 'image' => 'G_P1_Lead',
                'gun_class' => 'Heavy Weapon',
                'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 10}, "ammo": {"name": "Max Ammo", "value": 2400}, "rate": {"name": "Rate of Fire", "value": 30}, "reload": {"name": "Cooling Rate", "value": 1.5}, "ex1": {"name": "Spinup Time", "value": 0.7}, "ex2": {"name": "Spindown Time", "value": 3}, "ex3": {"name": "Base Spread", "value": 100, "percent": true}, "ex4": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex5": {"name": "Stun Chance", "value": 30, "percent": true}, "ex11": {"name": "Stun Duration", "value": 1}, "ex6": {"name": "Max Penetrations", "value": 0}, "ex7": {"name": "Max Stabilization Damage Bonus", "value": 0, "percent": true}, "ex8": {"name": "Critical Overheat", "value": 0, "boolean": true}, "ex9": {"name": "Heat Removed on Kill", "value": 0, "boolean": true}, "ex10": {"name": "Hot Bullets", "value": 0, "percent": true}, "ex12": {"name": "Movement Speed While Using", "value": 50, "percent": true}, "ex13": {"name": "Burning Hell", "value": 0, "boolean": true}, "ex14": {"name": "Heat Generation", "value": 100, "percent": true}, "ex15": {"name": "Ricochet chance on bullets", "value": 0, "boolean": true}}',
            ],
            [
                'name' => '"Thunderhead" Heavy Autocannon', 'character_id' => 4, 'image' => 'G_P2_Thunder',
                'gun_class' => 'Heavy Weapon', 'character_slot' => 1,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 14}, "ammo": {"name": "Max Ammo", "value": 440}, "clip": {"name": "Magazine Size", "value": 110}, "rate": {"name": "Top Rate of Fire", "value": 5.5}, "reload": {"name": "Reload Time", "value": 5}, "ex1": {"name": "Area Damage", "value": 9}, "ex2": {"name": "Effect Radius", "value": 1.4}, "ex3": {"name": "Base Spread", "value": 100, "percent": true}, "ex4": {"name": "Rate of Fire Growth Speed", "value": 100, "percent": true}, "ex5": {"name": "Armor Breaking", "value": 100, "percent": true}, "ex6": {"name": "Movement Speed While Using", "value": 50, "percent": true}, "ex7": {"name": "Top RoF Damage Bonus", "value": 0, "percent": true}, "ex8": {"name": "Impact Fear AoE", "value": 0}, "ex9": {"name": "Damage Resistance at Full RoF", "value": 0, "percent": true}, "ex10": {"name": "Neurotoxin Payload", "value": 0, "boolean": true}}',
            ],
            [
                'name' => '"Bulldog" Heavy Revolver', 'character_id' => 4, 'image' => 'G_S1_Bulldog',
                'gun_class' => 'Revolver',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 50}, "ammo": {"name": "Max Ammo", "value": 28}, "clip": {"name": "Magazine Size", "value": 4}, "rate": {"name": "Rate of Fire", "value": 2}, "reload": {"name": "Reload Time", "value": 2}, "ex1": {"name": "Base Spread", "value": 100, "percent": true}, "ex2": {"name": "Spread Per Shot", "value": 100, "percent": true}, "ex3": {"name": "Max Penetrations", "value": 0}, "ex4": {"name": "Stun Chance", "value": 50, "percent": true}, "ex10": {"name": "Stun Duration", "value": 1.5}, "ex5": {"name": "Area Damage", "value": 0}, "ex9": {"name": "Effect Radius", "value": 0}, "ex6": {"name": "Weakpoint Damage Bonus", "value": 15, "percent": true}, "ex7": {"name": "Dead-Eye", "value": 0, "boolean": true}, "ex8": {"name": "Neurotoxin Coating", "value": 0, "boolean": true}, "ex11": {"name": "Chain Hit", "value": 0, "boolean": true}, "ex12": {"name": "Magic Bullets", "value": 0, "boolean": true}, "ex13": {"name": "Recoil", "value": 100, "percent": true}, "ex14": {"name": "Damage Vs Burning", "value": 0, "percent": true}}',
            ],
            [
                'name' => 'BRT7 Burst Fire Gun', 'character_id' => 4, 'image' => 'G_S2_Burst', 'gun_class' => 'Pistol',
                'character_slot' => 2,
                'json_stats' => '{"dmg": {"name": "Damage", "value": 20}, "ammo": {"name": "Max Ammo", "value": 120}, "clip": {"name": "Magazine Size", "value": 24}, "rate": {"name": "Rate of Fire", "value": 2.5}, "reload": {"name": "Reload Time", "value": 2.2}, "ex1": {"name": "Burst Size", "value": 3}, "ex2": {"name": "Burst Speed", "value": 0.05}, "ex3": {"name": "Spread Per Shot", "value": 100, "percent": true}, "ex10": {"name": "Base Spread", "value": 100, "percent": true}, "ex4": {"name": "Recoil", "value": 100, "percent": true}, "ex5": {"name": "Armor Breaking", "value": 50, "percent": true}, "ex6": {"name": "Weakpoint Damage Bonus", "value": 0, "percent": true}, "ex7": {"name": "Burst Damage", "value": 0}, "ex8": {"name": "Burst Stun Duration", "value": 0}, "ex9": {"name": "Electro Minelets", "value": 0, "boolean": true}, "ex11": {"name": "Max Penetrations", "value": 1}}',
            ],
            [
                'name' => 'LOK-1 Smart Rifle', 'character_id' => 1, 'image' => 'E_P3_LOK1',
                'gun_class' => 'Submachine Gun', 'character_slot' => 1, 'json_stats' => '{}',
            ],
            [
                'name' => 'DRAK-25 Plasma Carbine', 'character_id' => 2, 'image' => 'S_P3_DRAK25',
                'gun_class' => 'Semi-Automatic Rifle',
                'character_slot' => 1,
                'json_stats' => '{}',
            ],
            [
                'name' => 'Corrosive Sludge Pump', 'character_id' => 3, 'image' => 'D_P3_Sludge',
                'gun_class' => 'Heavy Weapon',
                'character_slot' => 1,
                'json_stats' => '{}',
            ],
            [
                'name' => '"Hurricane" Guided Rocket System', 'character_id' => 4, 'image' => 'G_P3_Hurricane',
                'gun_class' => 'Heavy Weapon', 'character_slot' => 1,
                'json_stats' => '{}',
            ],
        ];

        foreach ($guns as $gun) {
            Gun::factory()->create($gun);
        }
    }
}

<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class BuildMetricSeederTableSeeder extends Seeder
{
    // Method Courtesy of: https://stackoverflow.com/a/52626485/7474537
    // Chunking our CSV to handle the file loading without killing the server/app
    // Grab a coffee after you run this, it will take a few minutes. Console will tell you which row we're on
    public function run()
    {
        $row = 1;
        if (($handle = fopen(base_path("database/seeds/build_metrics_import.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                if ($row === 1) {
                    $row++;
                    continue;
                }
                $row++;

                $dbData = [
                    'id'                               => '"'.$data[0].'"', 
                    'character_id'                     => '"'.$data[1].'"', 
                    'gun_id'                           => '"'.$data[2].'"', 
                    'weapon_short_name'                => '"'.$data[3].'"', 
                    'build_combination'                => '"'.$data[4].'"', 
                    'ideal_burst_dps'                  => '"'.$data[5].'"', 
                    'burst_dps_wp'                     => '"'.$data[6].'"', 
                    'burst_dps_acc'                    => '"'.$data[7].'"', 
                    'burst_dps_aw'                     => '"'.$data[8].'"', 
                    'burst_dps_wp_acc'                 => '"'.$data[9].'"', 
                    'burst_dps_wp_aw'                  => '"'.$data[10].'"', 
                    'burst_dps_acc_aw'                 => '"'.$data[11].'"', 
                    'burst_dps_wp_acc_aw'              => '"'.$data[12].'"', 
                    'ideal_sustained_dps'              => '"'.$data[13].'"', 
                    'sustained_dps_wp'                 => '"'.$data[14].'"', 
                    'sustained_dps_acc'                => '"'.$data[15].'"', 
                    'sustained_dps_aw'                 => '"'.$data[16].'"', 
                    'sustained_dps_wp_acc'             => '"'.$data[17].'"', 
                    'sustained_dps_wp_aw'              => '"'.$data[18].'"', 
                    'sustained_dps_acc_aw'             => '"'.$data[19].'"', 
                    'sustained_dps_wp_acc_aw'          => '"'.$data[20].'"', 
                    'ideal_additional_target_dps'      => '"'.$data[21].'"', 
                    'max_num_targets_per_shot'         => '"'.$data[22].'"', 
                    'max_multi_target_damage'          => '"'.$data[23].'"', 
                    'ammo_efficiency'                  => '"'.$data[24].'"', 
                    'damage_wasted_by_armor'           => '"'.$data[25].'"', 
                    'general_accuracy'                 => '"'.$data[26].'"', 
                    'weakpoint_accuracy'               => '"'.$data[27].'"', 
                    'firing_duration'                  => '"'.$data[28].'"', 
                    'average_time_to_kill'             => '"'.$data[29].'"', 
                    'average_overkill'                 => '"'.$data[30].'"', 
                    'breakpoints'                      => '"'.$data[31].'"', 
                    'utility'                          => '"'.$data[32].'"', 
                    'average_time_to_ignite_or_freeze' => '"'.$data[33].'"', 
                    'damage_per_magazine'              => '"'.$data[34].'"', 
                    'time_to_fire_magazine'            => '"'.$data[35].'"', 
                    'patch_id'                         => '"'.$data[36].'"', 
                ];

                

                $colNames = array_keys($dbData);

                $createQuery = 'INSERT INTO build_metrics ('.implode(',', $colNames).') VALUES ('.implode(',', $dbData).')';

                DB::statement($createQuery, $data);
                $this->command->info('Build Metrics Row: ' . $row);
            }
            fclose($handle);
        }
    }
}
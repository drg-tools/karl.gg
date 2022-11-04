<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OverclockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class OverclockCrudController.
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OverclockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Overclock::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/overclock');
        $this->crud->setEntityNameStrings('overclock', 'overclocks');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['overclock_name']); // make these the only columns in the table
        // '','gun_id'
        $this->crud->addColumn([
            'name' => 'character', // The db column name
            'label' => 'Character', // Table column heading
            'type' => 'relationship',
            'entity' => 'character', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\Character::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('character', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);
        $this->crud->addColumn([
            'name' => 'gun', // The db column name
            'label' => 'Gun', // Table column heading
            'type' => 'relationship',
            'entity' => 'gun', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\Gun::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('gun', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);

        $this->crud->addColumn('overclock_type');
        // add a "simple" filter called Class
        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'class',
            'label' => 'Class',
        ], [
            1 => 'Engineer',
            2 => 'Scout',
            3 => 'Driller',
            4 => 'Gunner',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'character_id', $value);
        });
        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'gun',
            'label' => 'Gun',
        ], [
            1 => '"Warthog" Auto 210',
            2 => '"Stubby" Voltaic SMG',
            3 => 'Deepcore 40MM PGL',
            4 => 'Breach Cutter',
            5 => 'Deepcore GK2',
            6 => 'M1000 Classic',
            7 => 'Jury-Rigged Boomstick',
            8 => 'Zhukov NUK17',
            9 => 'CRSPR Flamethrower',
            10 => 'Cryo Cannon',
            11 => 'Subata 120',
            12 => 'Experimental Plasma Charger',
            13 => '"Lead Storm" Powered Minigun',
            14 => '"Thunderhead" Heavy Autocannon',
            15 => '"Bulldog" Heavy Revolver',
            16 => 'BRT7 Burst Fire Gun',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'gun_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(OverclockRequest::class);

        $this->crud->addField([
            'name' => 'overclock_name',
            'label' => 'Overclock Name',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character_id', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => \App\Character::class,
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'gun_id', // the relationship name in your Model
            'entity' => 'gun', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => \App\Gun::class,
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'overclock_index',
            'label' => 'Overclock Index',
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'text_description',
            'label' => 'Text Description',
            'type' => 'textarea',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'select_from_array',
            // You can find this list in the `resources/js/IconsList.js`
            'options' => [
                'R_P1_Bosco_SVG' => 'R_P1_Bosco_SVG',
                'X_E_Armor_SVG' => 'X_E_Armor_SVG',
                'Icon_Upgrade_Distance' => 'Icon_Upgrade_Distance',
                'Icon_Upgrade_FireRate' => 'Icon_Upgrade_FireRate',
                'Icon_Upgrade_Fuel' => 'Icon_Upgrade_Fuel',
                'Class_level_icon' => 'Class_level_icon',
                'Icon_Upgrade_Accuracy' => 'Icon_Upgrade_Accuracy',
                'Icon_Upgrade_Aim' => 'Icon_Upgrade_Aim',
                'Icon_Upgrade_Ammo' => 'Icon_Upgrade_Ammo',
                'Icon_Upgrade_Angle' => 'Icon_Upgrade_Angle',
                'Icon_Upgrade_Area' => 'Icon_Upgrade_Area',
                'Icon_Upgrade_AreaDamage' => 'Icon_Upgrade_AreaDamage',
                'Icon_Upgrade_ArmorBreaking' => 'Icon_Upgrade_ArmorBreaking',
                'Icon_Upgrade_Arperture_Extension' => 'Icon_Upgrade_Arperture_Extension',
                'Icon_Upgrade_BulletPenetration' => 'Icon_Upgrade_BulletPenetration',
                'Icon_Upgrade_Capacity' => 'Icon_Upgrade_Capacity',
                'Icon_Upgrade_ChargeUp' => 'Icon_Upgrade_ChargeUp',
                'Icon_Upgrade_ClipSize' => 'Icon_Upgrade_ClipSize',
                'Icon_Upgrade_Cold' => 'Icon_Upgrade_Cold',
                'Icon_Upgrade_DamageGeneral' => 'Icon_Upgrade_DamageGeneral',
                'Icon_Upgrade_DefenseOne' => 'Icon_Upgrade_DefenseOne',
                'Icon_Upgrade_Digging' => 'Icon_Upgrade_Digging',
                'Icon_Upgrade_Duration' => 'Icon_Upgrade_Duration',
                'Icon_Upgrade_Duration_Old' => 'Icon_Upgrade_Duration_Old',
                'Icon_Upgrade_Electricity' => 'Icon_Upgrade_Electricity',
                'Icon_Upgrade_Explosion' => 'Icon_Upgrade_Explosion',
                'Icon_Upgrade_Explosive' => 'Icon_Upgrade_Explosive',
                'Icon_Upgrade_Explosive_Resistance' => 'Icon_Upgrade_Explosive_Resistance',
                'Icon_Upgrade_FallDamageResistance' => 'Icon_Upgrade_FallDamageResistance',
                'Icon_Upgrade_Fire_Resistance' => 'Icon_Upgrade_Fire_Resistance',
                'Icon_Upgrade_Flare_01' => 'Icon_Upgrade_Flare_01',
                'Icon_Upgrade_Heat' => 'Icon_Upgrade_Heat',
                'Icon_Upgrade_MovementSpeed' => 'Icon_Upgrade_MovementSpeed',
                'Icon_Upgrade_PoisonResistance' => 'Icon_Upgrade_PoisonResistance',
                'Icon_Upgrade_Poison_Resistance' => 'Icon_Upgrade_Poison_Resistance',
                'Icon_Upgrade_ProjectileSpeed' => 'Icon_Upgrade_ProjectileSpeed',
                'Icon_Upgrade_Recoil' => 'Icon_Upgrade_Recoil',
                'Icon_Upgrade_Resistance' => 'Icon_Upgrade_Resistance',
                'Icon_Upgrade_Revive' => 'Icon_Upgrade_Revive',
                'Icon_Upgrade_Ricoshet' => 'Icon_Upgrade_Ricoshet',
                'Icon_Upgrade_ScareEnemies' => 'Icon_Upgrade_ScareEnemies',
                'Icon_Upgrade_Shot' => 'Icon_Upgrade_Shot',
                'Icon_Upgrade_Shotgun_Pellet' => 'Icon_Upgrade_Shotgun_Pellet',
                'Icon_Upgrade_Shotgun_Pellet2' => 'Icon_Upgrade_Shotgun_Pellet2',
                'Icon_Upgrade_Speed' => 'Icon_Upgrade_Speed',
                'Icon_Upgrade_SpeedUp' => 'Icon_Upgrade_SpeedUp',
                'Icon_Upgrade_Sticky' => 'Icon_Upgrade_Sticky',
                'Icon_Upgrade_Stun' => 'Icon_Upgrade_Stun',
                'Icon_Upgrade_TemperatureCoolDown' => 'Icon_Upgrade_TemperatureCoolDown',
                'Icon_Upgrade_Weakspot' => 'Icon_Upgrade_Weakspot',
                'Icon_Upgrade_Bosco_Rocket_Upgrade' => 'Icon_Upgrade_Bosco_Rocket_Upgrade',
                'Icon_Upgrade_PlusOne' => 'Icon_Upgrade_PlusOne',
                'Icon_Upgrade_Light' => 'Icon_Upgrade_Light',
                'Icon_Upgrade_Light_ExtraLife' => 'Icon_Upgrade_Light_ExtraLife',
                'Icon_Upgrade_Light_Intensity' => 'Icon_Upgrade_Light_Intensity',
                'Icon_Upgrade_Special' => 'Icon_Upgrade_Special',
                'Icon_Upgrade_StrongerTurret' => 'Icon_Upgrade_StrongerTurret',
                'Icon_Upgrade_TwoTurrets' => 'Icon_Upgrade_TwoTurrets',
                'Icon_Overclock_ChangeOfHigherDamage' => 'Icon_Overclock_ChangeOfHigherDamage',
                'Icon_Overclock_ExplosionJump' => 'Icon_Overclock_ExplosionJump',
                'Icon_Overclock_ForthAndBack_Linecutter' => 'Icon_Overclock_ForthAndBack_Linecutter',
                'Icon_Overclock_LastShellHigherDamage' => 'Icon_Overclock_LastShellHigherDamage',
                'Icon_Overclock_Neuro' => 'Icon_Overclock_Neuro',
                'Icon_Overclock_ShotgunJump' => 'Icon_Overclock_ShotgunJump',
                'Icon_Overclock_Slowdown' => 'Icon_Overclock_Slowdown',
                'Icon_Overclock_SmallBullets' => 'Icon_Overclock_SmallBullets',
                'Icon_Overclock_Special_Magazine' => 'Icon_Overclock_Special_Magazine',
                'Icon_Overclock_Spinning_Linecutter' => 'Icon_Overclock_Spinning_Linecutter',
                'Player_State_Acid' => 'Player_State_Acid',
                'Icon_Overclock_Gamma_Contamination' => 'Icon_Overclock_Gamma_Contamination',
                'Icon_Upgrade_Pheremones' => 'Icon_Upgrade_Pheremones',
                'Icon_Upgrade_Radio_Control' => 'Icon_Upgrade_Radio_Control',
                'Icon_Upgrade_Damage_Poison' => 'Icon_Upgrade_Damage_Poison',
            ],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'overclock_type',
            'label' => 'Overclock Type',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'credits_cost',
            'label' => 'Credits Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'magnite_cost',
            'label' => 'Magnite Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'bismor_cost',
            'label' => 'Bismor Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'umanite_cost',
            'label' => 'Umanite Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'croppa_cost',
            'label' => 'Croppa Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'enor_pearl_cost',
            'label' => 'Enor Pearl Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'jadiz_cost',
            'label' => 'Jadiz Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => 'JSON Stats',
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->addField([
            'name' => 'overclock_name',
            'label' => 'Overclock Name',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'id',
            'label' => 'id',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ],
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character_id', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => \App\Character::class,
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'gun_id', // the relationship name in your Model
            'entity' => 'gun', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => \App\Gun::class,
            'tab' => 'Base Info',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'overclock_index',
            'label' => 'Overclock Index',
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'text_description',
            'label' => 'Text Description',
            'type' => 'textarea',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'select_from_array',
            // You can find this list in the `resources/js/IconsList.js`
            'options' => [
                'R_P1_Bosco_SVG' => 'R_P1_Bosco_SVG',
                'X_E_Armor_SVG' => 'X_E_Armor_SVG',
                'Icon_Upgrade_Distance' => 'Icon_Upgrade_Distance',
                'Icon_Upgrade_FireRate' => 'Icon_Upgrade_FireRate',
                'Icon_Upgrade_Fuel' => 'Icon_Upgrade_Fuel',
                'Class_level_icon' => 'Class_level_icon',
                'Icon_Upgrade_Accuracy' => 'Icon_Upgrade_Accuracy',
                'Icon_Upgrade_Aim' => 'Icon_Upgrade_Aim',
                'Icon_Upgrade_Ammo' => 'Icon_Upgrade_Ammo',
                'Icon_Upgrade_Angle' => 'Icon_Upgrade_Angle',
                'Icon_Upgrade_Area' => 'Icon_Upgrade_Area',
                'Icon_Upgrade_AreaDamage' => 'Icon_Upgrade_AreaDamage',
                'Icon_Upgrade_ArmorBreaking' => 'Icon_Upgrade_ArmorBreaking',
                'Icon_Upgrade_Arperture_Extension' => 'Icon_Upgrade_Arperture_Extension',
                'Icon_Upgrade_BulletPenetration' => 'Icon_Upgrade_BulletPenetration',
                'Icon_Upgrade_Capacity' => 'Icon_Upgrade_Capacity',
                'Icon_Upgrade_ChargeUp' => 'Icon_Upgrade_ChargeUp',
                'Icon_Upgrade_ClipSize' => 'Icon_Upgrade_ClipSize',
                'Icon_Upgrade_Cold' => 'Icon_Upgrade_Cold',
                'Icon_Upgrade_DamageGeneral' => 'Icon_Upgrade_DamageGeneral',
                'Icon_Upgrade_DefenseOne' => 'Icon_Upgrade_DefenseOne',
                'Icon_Upgrade_Digging' => 'Icon_Upgrade_Digging',
                'Icon_Upgrade_Duration' => 'Icon_Upgrade_Duration',
                'Icon_Upgrade_Duration_Old' => 'Icon_Upgrade_Duration_Old',
                'Icon_Upgrade_Electricity' => 'Icon_Upgrade_Electricity',
                'Icon_Upgrade_Explosion' => 'Icon_Upgrade_Explosion',
                'Icon_Upgrade_Explosive' => 'Icon_Upgrade_Explosive',
                'Icon_Upgrade_Explosive_Resistance' => 'Icon_Upgrade_Explosive_Resistance',
                'Icon_Upgrade_FallDamageResistance' => 'Icon_Upgrade_FallDamageResistance',
                'Icon_Upgrade_Fire_Resistance' => 'Icon_Upgrade_Fire_Resistance',
                'Icon_Upgrade_Flare_01' => 'Icon_Upgrade_Flare_01',
                'Icon_Upgrade_Heat' => 'Icon_Upgrade_Heat',
                'Icon_Upgrade_MovementSpeed' => 'Icon_Upgrade_MovementSpeed',
                'Icon_Upgrade_PoisonResistance' => 'Icon_Upgrade_PoisonResistance',
                'Icon_Upgrade_Poison_Resistance' => 'Icon_Upgrade_Poison_Resistance',
                'Icon_Upgrade_ProjectileSpeed' => 'Icon_Upgrade_ProjectileSpeed',
                'Icon_Upgrade_Recoil' => 'Icon_Upgrade_Recoil',
                'Icon_Upgrade_Resistance' => 'Icon_Upgrade_Resistance',
                'Icon_Upgrade_Revive' => 'Icon_Upgrade_Revive',
                'Icon_Upgrade_Ricoshet' => 'Icon_Upgrade_Ricoshet',
                'Icon_Upgrade_ScareEnemies' => 'Icon_Upgrade_ScareEnemies',
                'Icon_Upgrade_Shot' => 'Icon_Upgrade_Shot',
                'Icon_Upgrade_Shotgun_Pellet' => 'Icon_Upgrade_Shotgun_Pellet',
                'Icon_Upgrade_Shotgun_Pellet2' => 'Icon_Upgrade_Shotgun_Pellet2',
                'Icon_Upgrade_Speed' => 'Icon_Upgrade_Speed',
                'Icon_Upgrade_SpeedUp' => 'Icon_Upgrade_SpeedUp',
                'Icon_Upgrade_Sticky' => 'Icon_Upgrade_Sticky',
                'Icon_Upgrade_Stun' => 'Icon_Upgrade_Stun',
                'Icon_Upgrade_TemperatureCoolDown' => 'Icon_Upgrade_TemperatureCoolDown',
                'Icon_Upgrade_Weakspot' => 'Icon_Upgrade_Weakspot',
                'Icon_Upgrade_Bosco_Rocket_Upgrade' => 'Icon_Upgrade_Bosco_Rocket_Upgrade',
                'Icon_Upgrade_PlusOne' => 'Icon_Upgrade_PlusOne',
                'Icon_Upgrade_Light' => 'Icon_Upgrade_Light',
                'Icon_Upgrade_Light_ExtraLife' => 'Icon_Upgrade_Light_ExtraLife',
                'Icon_Upgrade_Light_Intensity' => 'Icon_Upgrade_Light_Intensity',
                'Icon_Upgrade_Special' => 'Icon_Upgrade_Special',
                'Icon_Upgrade_StrongerTurret' => 'Icon_Upgrade_StrongerTurret',
                'Icon_Upgrade_TwoTurrets' => 'Icon_Upgrade_TwoTurrets',
                'Icon_Overclock_ChangeOfHigherDamage' => 'Icon_Overclock_ChangeOfHigherDamage',
                'Icon_Overclock_ExplosionJump' => 'Icon_Overclock_ExplosionJump',
                'Icon_Overclock_ForthAndBack_Linecutter' => 'Icon_Overclock_ForthAndBack_Linecutter',
                'Icon_Overclock_LastShellHigherDamage' => 'Icon_Overclock_LastShellHigherDamage',
                'Icon_Overclock_Neuro' => 'Icon_Overclock_Neuro',
                'Icon_Overclock_ShotgunJump' => 'Icon_Overclock_ShotgunJump',
                'Icon_Overclock_Slowdown' => 'Icon_Overclock_Slowdown',
                'Icon_Overclock_SmallBullets' => 'Icon_Overclock_SmallBullets',
                'Icon_Overclock_Special_Magazine' => 'Icon_Overclock_Special_Magazine',
                'Icon_Overclock_Spinning_Linecutter' => 'Icon_Overclock_Spinning_Linecutter',
                'Player_State_Acid' => 'Player_State_Acid',
                'Icon_Overclock_Gamma_Contamination' => 'Icon_Overclock_Gamma_Contamination',
                'Icon_Upgrade_Pheremones' => 'Icon_Upgrade_Pheremones',
                'Icon_Upgrade_Damage_Poison' => 'Icon_Upgrade_Damage_Poison',
            ],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'overclock_type',
            'label' => 'Overclock Type',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'credits_cost',
            'label' => 'Credits Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'magnite_cost',
            'label' => 'Magnite Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'bismor_cost',
            'label' => 'Bismor Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'umanite_cost',
            'label' => 'Umanite Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'croppa_cost',
            'label' => 'Croppa Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'enor_pearl_cost',
            'label' => 'Enor Pearl Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'jadiz_cost',
            'label' => 'Jadiz Cost',
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => 'JSON Stats',
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);
    }
}

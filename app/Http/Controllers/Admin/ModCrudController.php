<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ModRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ModCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ModCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Mod');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/mod');
        $this->crud->setEntityNameStrings('mod', 'mods');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['mod_name']); // make these the only columns in the table
        // '','gun_id'
        $this->crud->addColumn([
            'name' => 'character', // The db column name
            'label' => 'Character', // Table column heading
            'type' => 'relationship',
            'entity' => 'character', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\Character::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('character', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'gun', // The db column name
            'label' => 'Gun', // Table column heading
            'type' => 'relationship',
            'entity' => 'gun', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\Gun::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('gun', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }
        ]);


        $this->crud->addColumns(['mod_tier', 'mod_index']);
        // add a "simple" filter called Class
        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'class',
            'label' => 'Class'
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
            'label' => 'Gun'
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
            16 => 'BRT7 Burst Fire Gun'
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'gun_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ModRequest::class);

        $this->crud->addField([
            'name' => 'mod_name',
            'label' => "Mod Name",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Character",
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'gun', // the relationship name in your Model
            'entity' => 'gun', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Gun",
            'tab' => 'Base Info',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'mod_tier',
            'label' => "Mod Tier",
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'mod_index',
            'label' => "Mod Index",
            'type' => 'select_from_array',
            'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C'],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'text_description',
            'label' => "Text Description",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => "Icon",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'mod_type',
            'label' => "Mod Type",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'patch_number_index',
            'label' => "Patch Number Index",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'credits_cost',
            'label' => "Credits Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'magnite_cost',
            'label' => "Magnite Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'bismor_cost',
            'label' => "Bismor Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'umanite_cost',
            'label' => "Umanite Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'croppa_cost',
            'label' => "Croppa Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'enor_pearl_cost',
            'label' => "Enor Pearl Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'jadiz_cost',
            'label' => "Jadiz Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => "JSON Stats",
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->crud->addField([
            'name' => 'mod_name',
            'label' => "Mod Name",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'id',
            'label' => "id",
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ],
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Character",
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'gun', // the relationship name in your Model
            'entity' => 'gun', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Gun",
            'tab' => 'Base Info',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'mod_tier',
            'label' => "Mod Tier",
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'mod_index',
            'label' => "Mod Index",
            'type' => 'select_from_array',
            'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C'],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'text_description',
            'label' => "Text Description",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => "Icon",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'mod_type',
            'label' => "Mod Type",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'patch_number_index',
            'label' => "Patch Number Index",
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'credits_cost',
            'label' => "Credits Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'magnite_cost',
            'label' => "Magnite Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'bismor_cost',
            'label' => "Bismor Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'umanite_cost',
            'label' => "Umanite Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'croppa_cost',
            'label' => "Croppa Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'enor_pearl_cost',
            'label' => "Enor Pearl Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'jadiz_cost',
            'label' => "Jadiz Cost",
            'type' => 'number',
            'tab' => 'Cost',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => "JSON Stats",
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);
    }
}

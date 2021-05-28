<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EquipmentModRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class EquipmentModCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EquipmentModCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\EquipmentMod');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/equipment-mod');
        $this->crud->setEntityNameStrings('equipment mod', 'equipment mods');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['mod_name']); // make these the only columns in the table
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
            'name' => 'equipment', // The db column name
            'label' => 'Equipment', // Table column heading
            'type' => 'relationship',
            'entity' => 'equipment', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\Equipment::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('equipment', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);

        $this->crud->addColumns(['mod_tier', 'mod_index']);
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
            'name' => 'equipment',
            'label' => 'Equipment',
        ], [
            1 => 'Reinforced Power Drills',
            2 => 'Satchel Charge',
            3 => 'Platform Gun',
            4 => 'LMG Gun Platform',
            5 => 'Zipline Launcher',
            6 => 'Shield Generator',
            7 => 'Grappling Hook',
            8 => 'Flare Gun',
            9 => 'Engineering Suit',
            10 => 'Light Scouting Suit',
            11 => 'Heavy Drill Suit',
            12 => 'Reinforced Impact Suit',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'equipment_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(EquipmentModRequest::class);

        $this->crud->addField([
            'name' => 'mod_name',
            'label' => 'Mod Name',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character_id', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Character",
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'equipment_id', // The db column name
            'label' => 'Equipment', // Table column heading
            'type' => 'relationship',
            'entity' => 'equipment', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Equipment", // foreign key model
            'tab' => 'Base Info',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'mod_tier',
            'label' => 'Mod Tier',
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'mod_index',
            'label' => 'Mod Index',
            'type' => 'select_from_array',
            'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C'],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Text Description',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'mod_type',
            'label' => 'Mod Type',
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
            'name' => 'mod_name',
            'label' => 'Mod Name',
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
            'model' => "App\Character",
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'equipment_id', // The db column name
            'label' => 'Equipment', // Table column heading
            'type' => 'relationship',
            'entity' => 'equipment', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Equipment", // foreign key model
            'tab' => 'Base Info',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'mod_tier',
            'label' => 'Mod Tier',
            'type' => 'select_from_array',
            'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'mod_index',
            'label' => 'Mod Index',
            'type' => 'select_from_array',
            'options' => ['A' => 'A', 'B' => 'B', 'C' => 'C'],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Text Description',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'mod_type',
            'label' => 'Mod Type',
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

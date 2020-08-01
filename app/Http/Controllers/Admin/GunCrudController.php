<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GunRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class GunCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GunCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Gun');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/gun');
        $this->crud->setEntityNameStrings('gun', 'guns');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['name', 'gun_class', 'character_slot']);
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
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(GunRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Gun Name',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => 'JSON Stats',
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'gun_class',
            'label' => 'Gun Class',
            'type' => 'select_from_array',
            'options' => [
                'Shotgun' => 'Shotgun',
                'Submachine Gun' => 'Submachine Gun',
                'Heavy Weapon' => 'Heavy Weapon',
                'Assault Rifle' => 'Assault Rifle',
                'Semi-Automatic Rifle' => 'Semi-Automatic Rifle',
                'Pistol' => 'Pistol',
                'Revolver' => 'Revolver',
            ],
            'allows_null' => false,
            'default' => '',
            'tab' => 'Base Info',
        ]);
        $this->crud->addField([
            'name' => 'character_slot',
            'label' => 'Character Slot',
            'type' => 'number',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Character",
        ]);
    }

    protected function setupUpdateOperation()
    {
        // $this->setupCreateOperation();
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Gun Name',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'json_stats',
            'label' => 'JSON Stats',
            'type' => 'textarea',
            'tab' => 'Stats',
        ]);
        $this->crud->addField([
            'name' => 'id',
            'label' => 'id',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);
        $this->crud->addField([   // select_from_array
            'name' => 'gun_class',
            'label' => 'Gun Class',
            'type' => 'select_from_array',
            'options' => [
                'Shotgun' => 'Shotgun',
                'Submachine Gun' => 'Submachine Gun',
                'Heavy Weapon' => 'Heavy Weapon',
                'Assault Rifle' => 'Assault Rifle',
                'Semi-Automatic Rifle' => 'Semi-Automatic Rifle',
                'Pistol' => 'Pistol',
                'Revolver' => 'Revolver',
            ],
            'allows_null' => false,
            'default' => '',
        ]);
        $this->crud->addField([
            'name' => 'character_slot',
            'label' => 'Character Slot',
            'type' => 'number',
        ]);
        $this->crud->addField([
            'type' => 'select',
            'name' => 'character', // the relationship name in your Model
            'entity' => 'character', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'model' => "App\Character",
        ]);
        $this->crud->addField([
            'name' => 'mods', // name of relationship method in the model
            'type' => 'relationship',
            'label' => 'Mods', // Table column heading
            'entity' => 'mods', // the relationship name in your Model
            'attribute' => 'mod_name', // attribute on Article that is shown to admin
            'model' => "App\Mod",
        ], );
        $this->crud->addField([
            'name' => 'overclocks', // name of relationship method in the model
            'type' => 'relationship',
            'label' => 'Overclocks', // Table column heading
            'entity' => 'overclocks', // the relationship name in your Model
            'attribute' => 'overclock_name', // attribute on Article that is shown to admin
            'model' => "App\Overclock",
        ], );
    }
}

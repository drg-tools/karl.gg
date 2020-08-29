<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoadoutRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class LoadoutCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LoadoutCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Loadout');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/loadout');
        $this->crud->setEntityNameStrings('loadout', 'loadouts');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['name']); // make these the only columns in the table
        $this->crud->addColumn([
            'name' => 'creator', // The db column name
            'label' => 'creator', // Table column heading
            'type' => 'relationship',
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => App\User::class, // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);
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
        $this->crud->addColumn([   // DateTime
            'name'  => 'created_at',
            'label' => 'Created',
            'type'  => 'datetime'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(LoadoutRequest::class);
        $this->crud->addFields(['name','description']); 
        $this->crud->addField([  // relationship
            'type' => "select2",
            'name' => 'creator', // the method on your model that defines the relationship
            'label' => "User",
            'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'creator', // the method that defines the relationship in your Model
            'model' => "App\User", // foreign key Eloquent model
            'placeholder' => "Select user", // placeholder for the select2 input
         ]);
         $this->crud->addField([  // relationship
            'type' => "select2",
            'name' => 'character_id', // the method on your model that defines the relationship
            'label' => "Character",
            'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'character', // the method that defines the relationship in your Model
            'model' => "App\Character", // foreign key Eloquent model
            'placeholder' => "Select character", // placeholder for the select2 input
         ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->addFields(['name','description']); 
        $this->crud->addField([  // relationship
            'type' => "select2",
            'name' => 'creator', // the method on your model that defines the relationship
            'label' => "User",
            'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'creator', // the method that defines the relationship in your Model
            'model' => "App\User", // foreign key Eloquent model
            'placeholder' => "Select user", // placeholder for the select2 input
         ]);
        $this->crud->addField([  // relationship
            'type' => "select2_multiple",
            'name' => 'mods', // the method on your model that defines the relationship
            'label' => "Mods",
            'attribute' => "mod_name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'mods', // the method that defines the relationship in your Model
            'model' => "App\Mod", // foreign key Eloquent model
            'placeholder' => "Select mods", // placeholder for the select2 input
         ]);
         $this->crud->addField([  // relationship
            'type' => "select2_multiple",
            'name' => 'overclocks', // the method on your model that defines the relationship
            'label' => "Overclocks",
            'attribute' => "overclock_name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'overclocks', // the method that defines the relationship in your Model
            'model' => "App\Overclock", // foreign key Eloquent model
            'placeholder' => "Select overclocks", // placeholder for the select2 input
         ]);
         $this->crud->addField([  // relationship
            'type' => "select2_multiple",
            'name' => 'equipment_mods', // the method on your model that defines the relationship
            'label' => "Equipment Mods",
            'attribute' => "mod_name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'equipment_mods', // the method that defines the relationship in your Model
            'model' => "App\EquipmentMod", // foreign key Eloquent model
            'placeholder' => "Select Equipment Mods", // placeholder for the select2 input
         ]);
         $this->crud->addField([  // relationship
            'type' => "select2",
            'name' => 'character', // the method on your model that defines the relationship
            'label' => "Character",
            'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
            'entity' => 'character', // the method that defines the relationship in your Model
            'model' => "App\Character", // foreign key Eloquent model
            'placeholder' => "Select character", // placeholder for the select2 input
         ]);
    }
}

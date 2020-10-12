<?php

namespace App\Http\Controllers\Admin;

use App\Character;
use App\Http\Requests\EquipmentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class EquipmentCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EquipmentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Equipment');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/equipment');
        $this->crud->setEntityNameStrings('equipment', 'equipment');
    }

    protected function setupListOperation()
    {
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

        $this->crud->addColumns(['name', 'json_stats']);

        $this->crud->addColumn([
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'closure',
            'function' => function ($entry) {
                return "<img src='/assets/{$entry->icon}.svg' style='width: 40px;' />";
            },
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(EquipmentRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'type' => 'select',
            'name' => 'character_id',
            'entity' => 'character',
            'attribute' => 'name',
            'model' => Character::class,
        ]);

        $this->crud->addField([
            'name' => 'json_stats',
            'label' => 'JSON Stats',
            'type' => 'textarea',
            'tab' => 'Stats',
            'default' => '{}',
        ]);

        $this->crud->addFields(['icon', 'eq_type']);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

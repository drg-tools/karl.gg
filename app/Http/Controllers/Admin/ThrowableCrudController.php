<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ThrowableRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class ThrowableCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ThrowableCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Throwable');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/throwable');
        $this->crud->setEntityNameStrings('throwable', 'throwables');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['name']); // make these the only columns in the table
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
        $this->crud->setValidation(ThrowableRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Throwable Name',
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
        $this->setupCreateOperation();
    }
}

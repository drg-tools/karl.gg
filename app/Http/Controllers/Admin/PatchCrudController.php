<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PatchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class PatchCrudController.
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PatchCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Patch');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/patch');
        $this->crud->setEntityNameStrings('patch', 'patches');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['patch_num', 'patch_num_dev', 'patch_title']);
        $this->crud->addColumn([
            'name' => 'launched_at',
            'label' => 'Launch Date',
            'type' => 'datetime',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PatchRequest::class);

        $this->crud->addField([
            'name' => 'patch_title',
            'label' => 'Patch Title',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'patch_num',
            'label' => 'Patch Number',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'patch_num_dev',
            'label' => 'Patch Number Dev',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'launched_at',
            'label' => 'Launch Date',
            'type' => 'datetime',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

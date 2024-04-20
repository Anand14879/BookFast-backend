<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DashboardControllerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DashboardControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardControllerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
  public function setup()
{
    // Set the page title
    $this->data['title'] = trans('backpack::base.dashboard');

    // Prepare the widgets array with the counts
    $widgets['before_content'] = [
        [
            'type'        => 'div',
            'class'       => 'row',
            'content'     => [ // widgets 
                [
                    'type'        => 'progress_white',
                    'class'       => 'card text-white bg-primary mb-2',
                    'value'       => \App\Models\Facility::count(),
                    'description' => 'Total Facilities',
                    'progress'    => (100), // Full progress bar
                    'progressClass' => 'progress-bar',
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card text-white bg-dark mb-2',
                    'value'       => \App\Models\User::count(),
                    'description' => 'Total Users',
                    'progress'    => (100), // Full progress bar
                    'progressClass' => 'progress-bar',
                ],
                // ... Add your other widgets here
            ]
        ],
    ];

    // Pass the widgets array to the view
    return view(backpack_view('dashboard'), [
        'widgets' => $widgets
    ]);
}



    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DashboardControllerRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

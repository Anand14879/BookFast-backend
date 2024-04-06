<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
//  use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\DB;
 use App\Models\Booking;
 use App\Models\Slot;

/**
 * Class BookingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookingCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Booking::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/booking');
        CRUD::setEntityNameStrings('booking', 'bookings');
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
        CRUD::setValidation(BookingRequest::class);
        CRUD::setFromDb(); // Set fields from the database
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

    public function destroy($id)
{
    // Check if the user has permission to delete
    $this->crud->hasAccessOrFail('delete');

    // Start a transaction to ensure both operations are completed or none are
    DB::beginTransaction();
    try {
        // Get the booking entry
        $booking = $this->crud->getEntry($id);

        // Get the associated slot ID from the booking model
        $slotId = $booking->slot_id; // Ensure this is the correct column name

        // Update the slot to set is_available to true
        Slot::where('id', $slotId)->update(['is_available' => true]);

        // Use the parent delete operation from Backpack to handle the actual deletion
        $response = $this->crud->delete($id);

        // Commit the transaction
        DB::commit();

        // Add a success message to the session
        \Alert::success('Booking deleted and slot availability updated.')->flash();
        return $response;
    } catch (\Throwable $e) {
        // Roll back the transaction on any error
        DB::rollBack();

        // Log the error
        \Log::error('Error occurred while deleting booking: ' . $e->getMessage());

        // Add an error message to the session
        \Alert::error('The booking could not be deleted.')->flash();

        // If there is an error, respond with a redirect to the previous page
        return back();
    }
}



}

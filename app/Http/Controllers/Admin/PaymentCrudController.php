<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Alert; 
use App\Http\Requests\PaymentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Payment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payment');
        CRUD::setEntityNameStrings('payment', 'payments');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('Payment ID');
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
        CRUD::setValidation(PaymentRequest::class);
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

    public function destroy($id)
{
    $payment = \App\Models\Payment::findOrFail($id);
    $booking = \App\Models\Booking::findOrFail($payment->booking_id);

    // Begin a transaction to ensure data integrity
    DB::beginTransaction();
    try {
        // Check if the booking status is 'Paid' then only we will save status as Refunded and update the Payment details
        if ($booking->status === 'Paid') {
            // Update booking status to 'Refunded'
            $booking->status = 'Refunded';
            $booking->save();

            // Update payment status to 'Refunded' instead of deleting it
            $payment->payment_status = 'Refunded';
            $payment->save();

            // Commit the transaction
            DB::commit();

            \Alert::success('Payment and Booking statuses updated to Refunded.')->flash();

            // Redirect to the payments list
            return redirect()->route('crud.payment.index');
        } else {
            // If the booking status is not 'Paid', do not update or delete
            DB::rollback();
            \Alert::error('Only paid bookings can be refunded.')->flash();
            return back();
        }
    } catch (\Exception $e) {
        // An error occurred; rollback and return error message
        DB::rollback();
        \Alert::error('An error occurred while refunding: ' . $e->getMessage())->flash();
        return back();
    }
}

}

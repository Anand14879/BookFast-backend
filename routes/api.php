<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::get('facilities', [FacilityController::class, 'index']);
Route::get('bookings/{userId}', [BookingController::class, 'index']);
Route::get('slots/{facilityId}', [SlotController::class, 'index']);
Route::post('saveforlater', [BookingController::class, 'saveForLater']);
Route::post('completebooking', [BookingController::class, 'completeBooking']);
Route::post('completebookingstatus', [BookingController::class, 'completeBookingStatus']);
Route::get('facility/details', [PaymentController::class, 'showFacilityDetails']);
Route::post('payment/add', [PaymentController::class, 'addPayment']);

Route::delete('booking/{id}', [BookingController::class, 'deleteBooking']);

Route::post('booking/refund/{bookingId}', [BookingController::class, 'refundBooking']);

Route::post('contacts', [ContactController::class, 'store']);

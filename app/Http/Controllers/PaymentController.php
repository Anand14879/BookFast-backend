<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\Booking;


class PaymentController extends Controller
{
    /**
     * Show details of a facility.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showFacilityDetails(Request $request)
    {
        $facilityId = $request->query('facility_id');
        $facility = Facility::find($facilityId);

        if (!$facility) {
            return response()->json(['error' => 'Facility not found'], 404);
        }

        return response()->json($facility);
    }

    /**
     * Add a new payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPayment(Request $request)
    {
        $paymentData = $request->validate([
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_status' => 'required|string',
            'booking_id' => 'required|integer|exists:bookings,id',
        ]);

        $payment = Payment::create($paymentData);

        // Update the booking status to 'Paid'
        $booking = Booking::find($paymentData['booking_id']);
        if ($booking) {
            $booking->update(['status' => 'Paid']); // Assuming you have a 'status' column in your bookings table
        }

        return response()->json($payment, 201);
    }
}

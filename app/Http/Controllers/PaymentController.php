<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

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
        // dd(paymentData);

        $payment = Payment::create($paymentData);

        // Update the booking status to 'Paid'
        $booking = Booking::find($paymentData['booking_id']);
        if ($booking) {
            $booking->update(['status' => 'Paid']); // Assuming you have a 'status' column in your bookings table
        }

        return response()->json($payment, 201);
    }

     public function initiatePayment(Request $request)
    {
        \Log::info('Incoming Request', ['request' => $request->all()]);
        $validatedData = $request->validate([
            'bookingId' => 'required',
            'amount' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);
       

        $khaltiSecretKey = env('KHALTI_SECRET_KEY'); //It is stored in the env file
        $khaltiPublicKey = env('KHALTI_PUBLIC_KEY'); //It is stored in the env file

        \Log::info('Khalti Public Key', ['key' => env('KHALTI_PUBLIC_KEY')]);
        \Log::info('Khalti Secret Key', ['key' => env('KHALTI_SECRET_KEY')]);



     $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $khaltiSecretKey, // Changed from 'Key' to 'Bearer'
    'Content-Type' => 'application/json',
])->post('https://a.khalti.com/api/v2/payment/initiate/', [ // Make sure this is the correct URL
    'public_key' => $khaltiPublicKey,
    'mobile' => '9840016420',
    'product_identity' => 'BookingID' . $validatedData['bookingId'],
    'product_name' => 'Booking Payment for ' . $validatedData['name'],
    'transaction_pin' => '987654', // This should be obtained securely and not hardcoded
    'amount' => $validatedData['amount'] * 100, // Amount in paisa
    'return_url' => 'http://127.0.0.1:3000/bookings',
    'website_url' => 'http://127.0.0.1:3000/home',
    'customer_info' => [
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone' => $validatedData['phone']
    ]
]);




        if ($response->successful()) {
        return response()->json($response->json(), 200);
        } else {
            \Log::error('Khalti Payment initiation failed', [
              'response' => $response->body()
    ]);
    return response()->json(['error' => 'Failed to initiate payment', 'details' => $response->json()], $response->status());
}

    }



}

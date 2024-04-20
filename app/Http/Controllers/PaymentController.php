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

//      public function initiatePayment(Request $request)
// {
//     \Log::info('Incoming Request', ['request' => $request->all()]);
//     $validatedData = $request->validate([
//         'bookingId' => 'required',
//         'amount' => 'required|integer',
//         'name' => 'required|string',
//         // 'email' => 'required|email',
//         // 'phone' => 'required|string',
//     ]);

//     $khaltiSecretKey = env('KHALTI_SECRET_KEY');
//     $khaltiPublicKey = env('KHALTI_PUBLIC_KEY');

//     \Log::info('Khalti Public Key', ['key' => $khaltiPublicKey]);
//     \Log::info('Khalti Secret Key', ['key' => $khaltiSecretKey]);

//     $response = Http::withHeaders([
//         'Authorization' => 'Key ' . $khaltiSecretKey,
//         'Content-Type' => 'application/json',
//     ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
//         // 'public_key' => $khaltiPublicKey,
//         // 'mobile' => $validatedData['phone'],
//         'purchase_order_id' => 'BookingID' . $validatedData['bookingId'],
//         'purchase_order_name' => 'Booking Payment for ' . $validatedData['name'],
//         // 'transaction_pin' => '1111', // This should be obtained securely and not hardcoded
//         'amount' => $validatedData['amount'] * 100,
//         'return_url' => 'http://127.0.0.1:3000/bookings',
//         'website_url' => 'http://127.0.0.1:3000/home',
//         // 'customer_info' => [
//         //     'name' => $validatedData['name'],
//         //     'email' => $validatedData['email'],
//         //     'phone' => $validatedData['phone'],
//         // ],
//     ]);

//     if ($response->successful()) {
//         return response()->json($response->json(), 200);
//     } else {
//         \Log::error('Khalti Payment initiation failed', [
//             'response' => $response->json(),
//             'status_code' => $response->status(),
//         ]);
//         return response()->json(['error' => 'Failed to initiate payment', 'details' => $response->json()], $response->status());
//     }
// }


//Second attempt
public function initiatePayment(Request $request)
{
    \Log::info('Incoming Request', ['request' => $request->all()]);
    $validatedData = $request->validate([
        'bookingId' => 'required',
        'amount' => 'required|integer|min:1',
        'name' => 'required|string',
        'email' => 'sometimes|required|email',
        'phone' => 'sometimes|required|string',
    ]);

    $khaltiSecretKey = env('KHALTI_SECRET_KEY');

    \Log::info('Khalti Secret Key', ['key' => $khaltiSecretKey]);

    $response = Http::withHeaders([
        'Authorization' => 'Key ' . $khaltiSecretKey, // API Authorization 
        'Content-Type' => 'application/json',
    ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
        'purchase_order_id' => 'BookingID' . $validatedData['bookingId'],
        'purchase_order_name' => 'Booking Payment for ' . $validatedData['name'],
        'amount' => $validatedData['amount'] * 100, // Convert amount to Paisa
        'return_url' => 'http://127.0.0.1:3000/bookings',
        'website_url' => 'http://127.0.0.1:3000/home',
        // 'mobile'=>$validatedData['phone'],
          'customer_info' => [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
        ],
    ]);

    if ($response->successful()) {
        return response()->json($response->json(), 200);
    } else {
        \Log::error('Khalti Payment initiation failed', [
            'response' => $response->json(),
            'status_code' => $response->status(),
        ]);
        return response()->json(['error' => 'Failed to initiate payment', 'details' => $response->json()], $response->status());
    }
}



}

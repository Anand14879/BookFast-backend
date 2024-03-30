<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Slot;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function index($userId)
    {
        // $bookings = Booking::all();
        // return response()->json($bookings);

        $booking = Booking::where('user_id', $userId)
                     ->get();

        return response()->json($booking);

    }


    public function saveForLater(Request $request)
    {
        DB::beginTransaction();
        try {
            $slot = Slot::findOrFail($request->input('slot_id'));
            if (!$slot->is_available) {
                DB::rollBack();
                return response()->json(['message' => 'Slot is not available'], 400);
            }
            $slot->is_available = false;
            $slot->save();

            $booking = Booking::create([
                'user_id' => $request->input('user_id'), // Ensure these keys match the request body
                'facility_id' => $request->input('facility_id'),
                'slot_id' => $request->input('slot_id'),
                'status' => 'Pending',
                'booking_date' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'Slot saved for later'], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to save the slot for later: " . $e->getMessage());

            // Send back a more detailed error message for debugging purposes.
            // Remove or modify this in production.
            return response()->json(['message' => 'Failed to save the slot for later', 'error' => $e->getMessage()], 500);
        }
    }
//Handles the Booking when we click on Complete Booking button
    public function completeBooking(Request $request)
{
    DB::beginTransaction();
    try {
        $slot = Slot::findOrFail($request->input('slot_id'));
        // Check if the slot is already booked
        if (!$slot->is_available) {
            DB::rollBack();
            return response()->json(['message' => 'This slot is already booked'], 400);
        }

        // Set the slot as unavailable
        $slot->is_available = false;
        $slot->save();

        // Create or update the booking with status 'Booked'
        $booking = Booking::updateOrCreate(
            [
                'slot_id' => $request->input('slot_id'),
                'user_id' => $request->input('user_id'), // Assuming you want to update for specific user
                // Add other conditions here if necessary
            ],
            [
                'facility_id' => $request->input('facility_id'),
                'status' => 'Booked',
                'booking_date' => now() // Use the current date-time or another field
            ]
        );

        DB::commit();
        return response()->json(['message' => 'Booking completed successfully'], 200);
    } catch (Throwable $e) {
        DB::rollBack();
        Log::error("Failed to complete the booking: " . $e->getMessage());

        // Send back a more detailed error message for debugging purposes.
        // Remove or modify this in production.
        return response()->json(['message' => 'Failed to complete the booking', 'error' => $e->getMessage()], 500);
    }
}
public function completeBookingStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::where('user_id', $request->user_id)
                        ->where('facility_id', $request->facility_id)
                        ->where('slot_id', $request->slot_id)
                        ->firstOrFail(); // Changed to firstOrFail to throw an exception if not found

            $booking->status = 'Booked';
            $booking->save();

            DB::commit();
            return response()->json(['message' => 'Booking status updated to Booked', 'booking' => $booking], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to update booking status: " . $e->getMessage());
            return response()->json(['error' => 'Server error', 'exception' => $e->getMessage()], 500);
        }
    }


}

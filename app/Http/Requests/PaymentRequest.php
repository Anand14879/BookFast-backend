<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Add this line

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules()
    {
        return [
            'booking_id' => [
                'required',
                'exists:bookings,id',
                Rule::unique('payments')->where(function ($query) {
                    return $query->where('booking_id', $this->booking_id);
                }),
                function ($attribute, $value, $fail) {
                    $booking = \App\Models\Booking::find($value);
                    if (!$booking || $booking->status !== 'Booked') {
                        $fail('The booking must have a status of "Booked" to proceed with payment.');
                    }
                },
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $booking = \App\Models\Booking::find($this->booking_id);
                    if ($booking && $booking->facility) {
                        $requiredAmount = $booking->facility->Daily_Cost; // Adjust the field name as necessary
                        if ($value < $requiredAmount) {
                            $fail("The {$attribute} must be at least {$requiredAmount}.");
                        }
                    }
                },
            ],
            'payment_date' => 'required|date', // You may specify the date format if needed
            // The payment_status rule will be removed because status will be set automatically
        ];
    }
    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}

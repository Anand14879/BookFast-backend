<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Add this line
use App\Models\Slot;


class BookingRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'facility_id' => 'required|exists:facilities,id',
            'slot_id' => [
                'required',
                'exists:slots,id',
                // Ensure the slot_id is unique for the facility_id and user_id except for the current booking id
                Rule::unique('bookings')->where(function ($query) {
                    return $query->where('facility_id', $this->facility_id)
                                 ->where('user_id', $this->user_id);
                })->ignore($this->id),

                // Check if the slot is available
                function ($attribute, $value, $fail) {
                    $slot = Slot::where('id', $value)
                                ->where('facility_id', $this->facility_id)
                                ->where('is_available', true)
                                ->first();
                    
                    if (!$slot) {
                        $fail('The selected slot is not available for the specified facility.');
                    }
                },
            ],
            
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

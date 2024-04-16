<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SlotRequest extends FormRequest
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
    return[
            'facility_id' => 'required|exists:facilities,id',
            'date' => [
                'required',
                'date',
                'after_or_equal:today', // Ensure date is today or a future date
                // Rule to ensure the date is unique for the given facility_id
                Rule::unique('slots')->where(function ($query) {
                    return $query->where('facility_id', request()->input('facility_id'));
                }),
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
            'facility_id.required' => 'The facility field is required.',
            'facility_id.exists' => 'The selected facility does not exist.',
        ];
    }
}

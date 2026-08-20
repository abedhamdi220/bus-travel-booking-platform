<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'cost' => ['required', 'integer', 'min:0'],
            'dateTrip' => ['required', 'date'],
            'timeTrip' => ['required', 'date_format:H:i'],
            'departure_city' => ['required', 'string', 'max:50'],
            'destination' => ['required', 'string', 'max:50'],
            'trip_type' => ['required', 'in:one_way,round_trip'],
            'recurrence' => ['required', 'in:daily,weekly,one_time'],
            'days' => ['nullable', 'array', 'required_if:recurrence,weekly'],
            'days.*' => ['in:sun,mon,tue,wed,thu,fri,sat'],
            'break_duration' => ['nullable', 'date_format:H:i', 'required_if:trip_type,round_trip'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParkingStartRequest extends FormRequest
{
    use FailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => [
                'required',
                'integer',
                Rule::exists(Vehicle::class, 'id')
                    ->whereNull('deleted_at')
                    ->where('user_id', auth()->id()),
//                'exists:vehicles,id,deleted_at,NULL,user_id,' . auth()->id(),
            ],
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
        ];
    }
}

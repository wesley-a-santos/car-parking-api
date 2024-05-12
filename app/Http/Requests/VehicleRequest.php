<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
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
            'plate_number' => [
                'required',
                Rule::unique(Vehicle::class, 'plate_number')
                ->ignore($this->plate_number, 'plate_number')
            ],
            'description' => ['required', 'max:255'],
        ];
    }
}

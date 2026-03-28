<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_plate' => ['required', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'engine_number' => ['nullable', 'string', 'max:50'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_address' => ['nullable', 'string', 'max:255'],
            'effective_date' => ['nullable', 'date'],
            'full_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
            'receiver_phone' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
        ];
    }
}

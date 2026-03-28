<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = (int) $this->route('id');
        $product = Product::find($productId);
        $isEpass = $product?->category === 'EPASS';

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'identity_card' => ['required', 'string', 'max:32'],
            'cavet_image' => ['required', 'image', 'max:5120'],
            'front_vehicle_image' => [$isEpass ? 'required' : 'nullable', 'image', 'max:5120'],
            'inspection_image' => [$isEpass ? 'required' : 'nullable', 'image', 'max:5120'],
            'license_plate' => ['required', 'string', 'max:20'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'engine_number' => ['nullable', 'string', 'max:50'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_address' => ['nullable', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'plate_color' => ['required', 'string', 'max:50'],
            'payload' => ['nullable', 'string', 'max:50'],
            'seat_capacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'effective_date' => [$isEpass ? 'nullable' : 'required', 'date'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCompleted = $this->input('status') === 'completed';

        return [
            'status' => ['required', Rule::in(['pending', 'pending_verification', 'paid', 'completed'])],
            'insurance_link' => ['nullable', 'url', Rule::requiredIf($isCompleted)],
            'tracking_code' => ['nullable', 'string', 'max:100'],
        ];
    }
}

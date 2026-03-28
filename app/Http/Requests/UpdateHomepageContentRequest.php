<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomepageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $textRules = ['nullable', 'string', 'max:255'];
        $longTextRules = ['nullable', 'string', 'max:1000'];
        $imageRules = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

        return [
            'hero_kicker' => $textRules,
            'hero_title' => ['required', 'string', 'max:220'],
            'hero_description' => ['required', 'string', 'max:600'],
            'hero_primary_cta_text' => ['required', 'string', 'max:60'],
            'hero_secondary_cta_text' => ['required', 'string', 'max:60'],
            'hero_point_1' => ['required', 'string', 'max:160'],
            'hero_point_2' => ['required', 'string', 'max:160'],
            'hero_point_3' => ['required', 'string', 'max:160'],
            'slide_1_eyebrow' => $textRules,
            'slide_1_title' => ['required', 'string', 'max:220'],
            'slide_1_text' => $longTextRules,
            'slide_2_eyebrow' => $textRules,
            'slide_2_title' => ['required', 'string', 'max:220'],
            'slide_2_text' => $longTextRules,
            'slide_3_eyebrow' => $textRules,
            'slide_3_title' => ['required', 'string', 'max:220'],
            'slide_3_text' => $longTextRules,
            'slide_1_image' => $imageRules,
            'slide_2_image' => $imageRules,
            'slide_3_image' => $imageRules,
            'insurance_kicker' => $textRules,
            'insurance_title' => ['required', 'string', 'max:220'],
            'insurance_description' => $longTextRules,
            'epass_kicker' => $textRules,
            'epass_title' => ['required', 'string', 'max:220'],
            'epass_description' => $longTextRules,
            'customer_info_title' => ['required', 'string', 'max:220'],
            'customer_info_description' => $longTextRules,
            'operator_info_title' => ['required', 'string', 'max:220'],
            'operator_info_description' => $longTextRules,
        ];
    }
}

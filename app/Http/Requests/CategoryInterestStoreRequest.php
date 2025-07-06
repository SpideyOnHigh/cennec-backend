<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryInterestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interest_name' => [
                'required',
                'string',
                'min:2',
            ],
            'interest_color' => [
                'required',
                'string',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            // 'sponsor_id' => [
            //     'required',
            //     'exists:users,id',
            // ],
            'interest_category_id' => [
                'required',
                'exists:interest_category_masters,id',
            ],
            'description_link' => [
                'nullable',
                'url',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'interest_name.required' => 'Please enter an interest name.',
            'interest_name.min' => 'The interest name must be at least 2 characters long.',
            'interest_color.required' => 'Please select a color.',
            'interest_color.regex' => 'The color must be a valid hex color code.',
            'sponsor_id.required' => 'Please select a sponsor.',
            'sponsor_id.exists' => 'The selected sponsor is invalid.',
            'interest_category_id.required' => 'Please select a category.',
            'interest_category_id.exists' => 'The selected category is invalid.',
            'description_link.required' => 'Please enter a description link.',
            'description_link.url' => 'Please enter a valid URL.',
        ];
    }
}

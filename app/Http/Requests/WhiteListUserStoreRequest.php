<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhiteListUserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'is_domain' => 'required|string|max:255',
        ];
    }
}

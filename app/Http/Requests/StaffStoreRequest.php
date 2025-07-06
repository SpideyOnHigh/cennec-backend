<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('staff') ? $this->route('staff')->id : null;
        return [
            'name' => 'required|string|max:255',
            'contact' => 'required|digits:10|unique:users,contact',
            'role' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($userId)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password' => 'The password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ];
    }
}

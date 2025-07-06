<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
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
        $user = $this->route('staff');
        return [
            'name' => 'required|string|max:255',
            'contact' => 'required|digits:10|unique:users,contact,' . $user->id,
            'role' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];
    }

    public function messages(): array
    {
        return [
            'password' => 'The password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ];
    }
}

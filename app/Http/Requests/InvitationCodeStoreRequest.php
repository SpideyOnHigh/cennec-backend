<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationCodeStoreRequest extends FormRequest
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
        return [
            'code' => 'required|unique:invitation_code_masters,code',
            'sponsor_id' => 'required|integer',
            'expiration_date' => 'required|date',
            'max_user_allow' => 'required|integer|min:1',
            'comment' => 'required|string|max:255',
        ];
    }
}

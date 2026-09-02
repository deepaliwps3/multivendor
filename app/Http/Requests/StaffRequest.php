<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Update route par {staff} model bind hota hai, create par nahi.
        $staff = $this->route('staff');
        $staffId = $staff?->id;

        // isMethod('PUT') ya isMethod('PATCH') = update request
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staffId)],
            'phone'    => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($staffId)],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8'],
            'address'  => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * Custom validation messages (optional, isse aap apni marzi ke error text de sakte ho).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already registered.',
        ];
    }
}

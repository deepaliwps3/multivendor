<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffUpdateRequest extends FormRequest
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
        $staffId = $this->route('staff')?->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staffId)],
            'phone'    => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($staffId)],
            'password' => ['nullable', 'string', 'min:8'],
            'address'  => ['required', 'string', 'max:500'],
        ];
    }
}

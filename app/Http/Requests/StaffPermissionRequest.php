<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffPermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        $staffPermissionId = $this->route('staff_permission')?->id;

        return [
            'staff_id' => ['required', 'integer', 'exists:users,id'],
            'permission_key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('staff_permissions', 'permission_key')
                    ->where(fn($query) => $query->where('staff_id', $this->staff_id))
                    ->ignore($staffPermissionId),
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorRequest extends FormRequest
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
        return [
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->vendor->user_id ?? null)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => $this->isMethod('POST') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'business_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            // 'vendor_type' => ['required', Rule::in(['originator', 'executor', 'both'])],
            'kyc_status' => ['required', Rule::in(['pending', 'verified', 'rejected'])],
            'approval_status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
            'industries' => ['nullable', 'array'],
            'industries.*' => ['exists:industries,id'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'order_stage_id' => ['required', 'integer', 'exists:order_stages,id'],
            'payer_id'       => ['required', 'integer', 'exists:users,id'],
            'payee_id'       => ['required', 'integer', 'exists:users,id', 'different:payer_id'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'status'         => ['required', 'string', 'in:pending,released,failed,refunded'],
            'released_at'    => ['nullable', 'date'],
        ];
    }
}

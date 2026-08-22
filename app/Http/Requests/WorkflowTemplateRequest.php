<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkflowTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // route model binding param ka naam: workflowTemplate (Route::resource se aata hai)
        $templateId = $this->route('workflow_template')?->id ?? $this->route('workflowTemplate')?->id;

        return [
            'industry_id' => ['required', 'integer', 'exists:industries,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('workflow_templates', 'name')
                    ->where(fn($q) => $q->where('industry_id', $this->input('industry_id')))
                    ->ignore($templateId),
            ],

            'stages' => ['required', 'array', 'min:1'],
            'stages.*.id' => ['nullable', 'integer', 'exists:workflow_template_stages,id'],
            'stages.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'stages.*.sequence_no' => ['required', 'integer', 'min:1'],
            'stages.*.is_mandatory' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'industry_id.required' => 'Industry select karna zaroori hai.',
            'name.required' => 'Template name required hai.',
            'name.unique' => 'Is industry ke liye ye template name pehle se maujood hai.',
            'stages.required' => 'Kam se kam ek stage add karna zaroori hai.',
            'stages.min' => 'Kam se kam ek stage add karna zaroori hai.',
            'stages.*.service_id.required' => 'Har stage ke liye service select karna zaroori hai.',
            'stages.*.sequence_no.required' => 'Har stage ka sequence number zaroori hai.',
        ];
    }

    /**
     * Sequence numbers ko validate hone se pehle normalize kar dete hain
     * (agar frontend se string aaye to bhi sahi se compare ho).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('stages') && is_array($this->stages)) {
            $this->merge([
                'stages' => collect($this->stages)->map(function ($stage) {
                    $stage['is_mandatory'] = filter_var($stage['is_mandatory'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    return $stage;
                })->all(),
            ]);
        }
    }
}

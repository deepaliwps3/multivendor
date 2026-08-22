<?php

namespace App\Services\Backend;

use App\Models\WorkflowTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WorkflowTemplateService
{
    /**
     * Get the base query for DataTables server-side listing.
     */
    public function getTemplatesQuery(): Builder
    {
        return WorkflowTemplate::query()
            ->with(['industry', 'stages'])
            ->withCount('stages');
    }

    /**
     * Find a single template along with its industry and stages (with services).
     */
    public function getTemplateWithStages(WorkflowTemplate $template): WorkflowTemplate
    {
        return $template->load(['industry', 'stages.service']);
    }

    /**
     * Create a new workflow template along with its stages inside a DB transaction.
     */
    public function createTemplate(array $data): WorkflowTemplate
    {
        return DB::transaction(function () use ($data) {
            $template = WorkflowTemplate::create([
                'industry_id' => $data['industry_id'],
                'name' => $data['name'],
            ]);

            $this->syncStages($template, $data['stages']);

            return $template->load(['industry', 'stages.service']);
        });
    }

    /**
     * Update an existing workflow template along with its stages inside a DB transaction.
     */
    public function updateTemplate(WorkflowTemplate $template, array $data): WorkflowTemplate
    {
        return DB::transaction(function () use ($template, $data) {
            $template->update([
                'industry_id' => $data['industry_id'],
                'name' => $data['name'],
            ]);

            $this->syncStages($template, $data['stages']);

            return $template->load(['industry', 'stages.service']);
        });
    }

    /**
     * Delete a workflow template along with its stages inside a DB transaction.
     */
    public function deleteTemplate(WorkflowTemplate $template): void
    {
        DB::transaction(function () use ($template) {
            $template->stages()->delete();
            $template->delete();
        });
    }

    /**
     * Create, update, or remove stages based on the incoming payload.
     * Stage ids jo payload mein nahi aati unko delete kar diya jata hai
     * (form se remove ki hui maani jaati hain).
     */
    protected function syncStages(WorkflowTemplate $template, array $stages): void
    {
        $incomingIds = [];

        foreach ($stages as $stageData) {
            $stage = $template->stages()->updateOrCreate(
                ['id' => $stageData['id'] ?? null],
                [
                    'service_id' => $stageData['service_id'],
                    'sequence_no' => $stageData['sequence_no'],
                    'is_mandatory' => $stageData['is_mandatory'] ?? false,
                ]
            );

            $incomingIds[] = $stage->id;
        }

        $template->stages()->whereNotIn('id', $incomingIds)->delete();
    }
}

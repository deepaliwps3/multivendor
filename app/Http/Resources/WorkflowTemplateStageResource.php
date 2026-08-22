<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkflowTemplateStageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'template_id' => $this->template_id,
            'service_id' => $this->service_id,
            'service_name' => $this->whenLoaded('service', fn() => $this->service->name),
            'sequence_no' => $this->sequence_no,
            'is_mandatory' => (bool) $this->is_mandatory,
        ];
    }
}

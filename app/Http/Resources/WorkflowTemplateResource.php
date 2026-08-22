<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkflowTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'industry_id' => $this->industry_id,
            'industry_name' => $this->whenLoaded('industry', fn() => $this->industry->name),
            'stages_count' => $this->whenCounted('stages'),
            'stages' => WorkflowTemplateStageResource::collection($this->whenLoaded('stages')),
            'created_at' => $this->created_at?->format('d-m-Y h:i A'),
            'updated_at' => $this->updated_at?->format('d-m-Y h:i A'),
        ];
    }
}

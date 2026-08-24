<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'uuid'           => $this->uuid,
            'order_stage_id' => $this->order_stage_id,
            'payer'          => [
                'id'   => $this->payer?->id,
                'name' => $this->payer?->name,
            ],
            'payee'          => [
                'id'   => $this->payee?->id,
                'name' => $this->payee?->name,
            ],
            'amount'         => (float) $this->amount,
            'status'         => $this->status,
            'status_label'   => ucfirst($this->status),
            'released_at'    => $this->released_at?->toDateTimeString(),
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}

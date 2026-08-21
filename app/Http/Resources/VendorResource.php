<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'business_name' => $this->business_name,
            'contact_person' => $this->contact_person,
            'address' => $this->address,
            'gst_number' => $this->gst_number,
            'vendor_type' => $this->vendor_type,
            'kyc_status' => $this->kyc_status,
            'approval_status' => $this->approval_status,
            'industries' => $this->whenLoaded('industries', function () {
                return IndustryResource::collection($this->industries);
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

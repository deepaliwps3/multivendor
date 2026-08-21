<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['industry_id', 'name', 'description'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function vendorServices()
    {
        return $this->hasMany(VendorService::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_services')
            ->withPivot('price_per_unit', 'is_active')
            ->withTimestamps();
    }

    public function workflowTemplateStages()
    {
        return $this->hasMany(WorkflowTemplateStage::class);
    }

    public function orderStages()
    {
        return $this->hasMany(OrderStage::class);
    }
}

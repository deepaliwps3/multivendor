<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'industry_id', 'name', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            $service->uuid = (string) Str::uuid();
        });
    }

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

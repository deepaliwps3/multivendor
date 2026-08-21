<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'kyc_status',
        'approval_status',
        'vendor_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'vendor_industries');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'vendor_services')
            ->withPivot('price_per_unit', 'is_active')
            ->withTimestamps();
    }

    public function vendorServices()
    {
        return $this->hasMany(VendorService::class);
    }

    public function originatedOrders()
    {
        return $this->hasMany(Order::class, 'originating_vendor_id');
    }

    public function assignedOrderStages()
    {
        return $this->hasMany(OrderStage::class, 'assigned_vendor_id');
    }
}

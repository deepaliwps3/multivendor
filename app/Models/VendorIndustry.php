<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorIndustry extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'industry_id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VendorIndustry extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'vendor_id', 'industry_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}

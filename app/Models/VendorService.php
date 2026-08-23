<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VendorService extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'vendor_id', 'service_id', 'price_per_unit', 'is_active'];

    protected static function booted()
    {
        static::creating(function ($vendorService) {
            $vendorService->uuid = (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price_per_unit' => 'decimal:2',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

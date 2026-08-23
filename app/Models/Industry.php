<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($industry) {
            if (empty($industry->uuid)) {
                $industry->uuid = Str::uuid()->toString();
            }
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_industries');
    }

    public function workflowTemplates()
    {
        return $this->hasMany(WorkflowTemplate::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

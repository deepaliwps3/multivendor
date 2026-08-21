<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'workflow_template_id',
        'originating_vendor_id',
        'status',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function workflowTemplate()
    {
        return $this->belongsTo(WorkflowTemplate::class);
    }

    public function originatingVendor()
    {
        return $this->belongsTo(Vendor::class, 'originating_vendor_id');
    }

    public function stages()
    {
        return $this->hasMany(OrderStage::class)->orderBy('sequence_no');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

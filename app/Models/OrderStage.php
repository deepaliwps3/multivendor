<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'sequence_no',
        'assigned_vendor_id',
        'assigned_by',
        'status',
        'amount',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedVendor()
    {
        return $this->belongsTo(Vendor::class, 'assigned_vendor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function history()
    {
        return $this->hasMany(OrderStageHistory::class)->orderBy('changed_at');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

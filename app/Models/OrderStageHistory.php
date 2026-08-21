<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStageHistory extends Model
{
    use HasFactory;

    protected $table = 'order_stage_history';

    protected $fillable = [
        'order_stage_id',
        'changed_by',
        'from_status',
        'to_status',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function orderStage()
    {
        return $this->belongsTo(OrderStage::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_stage_id',
        'payer_id',
        'payee_id',
        'amount',
        'status',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'released_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function orderStage()
    {
        return $this->belongsTo(OrderStage::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}

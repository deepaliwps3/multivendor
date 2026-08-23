<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_stage_id',
        'payer_id',
        'payee_id',
        'amount',
        'status',
        'released_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            $payment->uuid = (string) Str::uuid();
        });
    }

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

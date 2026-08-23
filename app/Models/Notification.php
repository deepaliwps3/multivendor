<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['uuid', 'user_id', 'message', 'order_id', 'read_status'];

    protected static function booted(): void
    {
        static::creating(function ($notification) {
            $notification->uuid = (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'read_status' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

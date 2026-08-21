<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPermission extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'permission_key'];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}

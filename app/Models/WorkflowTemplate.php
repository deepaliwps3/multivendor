<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkflowTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'industry_id', 'name'];

    protected static function booted()
    {
        static::creating(function ($workflowTemplate) {
            $workflowTemplate->uuid = (string) Str::uuid();
        });
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function stages()
    {
        return $this->hasMany(WorkflowTemplateStage::class, 'template_id')->orderBy('sequence_no');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

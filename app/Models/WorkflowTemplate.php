<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['industry_id', 'name'];

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

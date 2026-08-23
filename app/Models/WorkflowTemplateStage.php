<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkflowTemplateStage extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'template_id', 'service_id', 'sequence_no', 'is_mandatory'];

    protected static function booted()
    {
        static::creating(function ($workflowTemplateStage) {
            $workflowTemplateStage->uuid = (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
        ];
    }

    public function template()
    {
        return $this->belongsTo(WorkflowTemplate::class, 'template_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

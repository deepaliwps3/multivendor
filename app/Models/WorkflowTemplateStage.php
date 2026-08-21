<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTemplateStage extends Model
{
    use HasFactory;

    protected $fillable = ['template_id', 'service_id', 'sequence_no', 'is_mandatory'];

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

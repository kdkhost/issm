<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSupportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'project_support_type_id',
        'supporter_type',
        'name',
        'email',
        'phone',
        'document',
        'organization',
        'government_agency',
        'amount',
        'currency',
        'payment_gateway',
        'payment_method',
        'payment_status',
        'payment_external_id',
        'payment_reference',
        'payment_url',
        'payment_payload',
        'item_description',
        'quantity',
        'unit',
        'address',
        'message',
        'preferred_contact',
        'status',
        'metadata',
        'ip_address',
        'user_agent',
        'contacted_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'metadata' => 'array',
        'payment_payload' => 'array',
        'contacted_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function supportType()
    {
        return $this->belongsTo(ProjectSupportType::class, 'project_support_type_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}

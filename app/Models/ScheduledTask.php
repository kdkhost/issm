<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    protected $fillable = ['command', 'description', 'frequency', 'active', 'last_run_at', 'next_run_at'];

    protected $casts = [
        'active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public static function defaultTasks(): array
    {
        return [
            [
                'command' => 'transparency:sync-drive',
                'description' => 'Sincroniza documentos do Google Drive com o Portal da Transparencia',
                'frequency' => 'daily',
                'active' => false,
            ],
        ];
    }
}

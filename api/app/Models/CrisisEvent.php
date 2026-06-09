<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrisisEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trigger_source',
        'content',
        'severity',
        'keywords_detected',
        'response_action',
        'resolved',
    ];

    protected function casts(): array
    {
        return [
            'keywords_detected' => 'array',
            'resolved' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

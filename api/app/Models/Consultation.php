<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'user_id',
        'professional_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'amount',
        'jitsi_room',
        'share_assessments',
        'share_mood_logs',
        'recording_enabled',
        'recording_url',
        'recording_kept',
        'recording_deleted',
        'notes_requested_at',
        'is_follow_up',
        'parent_consultation_id',
        'professional_notes',
        'user_rating',
        'user_review',
        'actual_start',
        'actual_end',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'actual_start' => 'datetime',
            'actual_end' => 'datetime',
            'notes_requested_at' => 'datetime',
            'share_assessments' => 'boolean',
            'share_mood_logs' => 'boolean',
            'recording_enabled' => 'boolean',
            'recording_kept' => 'boolean',
            'recording_deleted' => 'boolean',
            'is_follow_up' => 'boolean',
            'amount' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payout()
    {
        return $this->hasOne(ProfessionalPayout::class);
    }
}

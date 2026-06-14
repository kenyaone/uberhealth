<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kmpdc_license',
        'verification_status',
        'rate_per_hour',
        'bio',
        'years_experience',
        'gender',
        'rating',
        'total_sessions',
        'total_reviews',
        'is_available_online',
        'is_accepting_new_patients',
        'profile_photo',
        'mpesa_number',
        'location_city',
        'location_county',
        'latitude',
        'longitude',
        'tribe',
        'date_of_birth',
        'cpb_license',
        'is_available_physical',
        'service_areas',
        'current_service_area',
    ];

    protected function casts(): array
    {
        return [
            'is_available_online' => 'boolean',
            'is_accepting_new_patients' => 'boolean',
            'is_available_physical' => 'boolean',
            'rating' => 'float',
            'rate_per_hour' => 'float',
            'latitude' => 'float',
            'longitude' => 'float',
            'date_of_birth' => 'date',
            'service_areas' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'professional_specialization');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'professional_language');
    }

    public function availability()
    {
        return $this->hasMany(ProfessionalAvailability::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function payouts()
    {
        return $this->hasMany(ProfessionalPayout::class);
    }
}

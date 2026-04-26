<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'pet_id', 'status', 'home_type', 'household_members',
        'has_other_pets', 'other_pets_details', 'yard_available',
        'experience_with_pets', 'employment_sustainability', 'reason_for_adoption', 
        'references', 'additional_information', 'submitted_at', 'reviewed_at', 'review_notes'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'has_other_pets' => 'boolean',
        'yard_available' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}

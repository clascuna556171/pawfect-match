<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'species', 'breed', 'age_months', 'size', 'gender',
        'description', 'personality', 'temperament', 'energy_level',
        'health_status', 'adoption_status', 'vaccinated', 'neutered', 'good_with_kids',
        'good_with_pets', 'image_url', 'gallery', 'featured_video', 'adoption_fee',
        'requirements', 'medical_history', 'medical_notes', 'dietary_requirements',
        'intake_date', 'adopted_date'
    ];

    protected $casts = [
        'personality' => 'array',
        'temperament' => 'array',
        'gallery' => 'array',
        'dietary_requirements' => 'array',
        'requirements' => 'array',
        'medical_history' => 'array',
        'vaccinated' => 'boolean',
        'neutered' => 'boolean',
        'good_with_kids' => 'boolean',
        'good_with_pets' => 'boolean',
        'adoption_fee' => 'decimal:2',
        'intake_date' => 'date',
        'adopted_date' => 'date',
    ];

    public function getFormattedAgeAttribute()
    {
        $ageMonths = (int) ($this->age_months ?? 0);
        $years = floor($ageMonths / 12);
        $months = $ageMonths % 12;
        
        if ($years > 0 && $months > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '') . ", {$months} month" . ($months > 1 ? 's' : '');
        } elseif ($years > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '');
        } else {
            return "{$months} month" . ($months > 1 ? 's' : '');
        }
    }

    public function scopeAvailable($query)
    {
        return $query->where('adoption_status', 'Available');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public static function speciesOptions(): array
    {
        return ['Dog', 'Cat', 'Rabbit', 'Hamster', 'Bird', 'Other'];
    }

    public static function sizeOptions(): array
    {
        return ['Small', 'Medium', 'Large', 'Extra Large'];
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
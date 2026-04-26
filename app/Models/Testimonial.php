<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'adopter_name',
        'pet_name',
        'story_text',
        'image_path',
        'is_approved',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_name', 'name');
    }
}

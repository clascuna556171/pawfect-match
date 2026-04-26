<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetUpdate extends Model
{
    protected $fillable = [
        'adoption_id',
        'status_message',
        'image_path',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'adoption_id');
    }
}

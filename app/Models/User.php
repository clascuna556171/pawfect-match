<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_STAFF = 'staff';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function favorites()
    {
        return $this->belongsToMany(Pet::class, 'favorites')->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function hasFavorited($petId)
    {
        return $this->favorites()->where('pet_id', $petId)->exists();
    }
}
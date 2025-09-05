<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamentos
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function programs()
    {
        return $this->belongsToMany(program::class, 'applications')
                    ->withPivot('status', 'motivation', 'created_at', 'updated_at')
                    ->withTimestamps();
    }
}

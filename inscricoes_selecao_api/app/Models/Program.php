<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'max_candidates',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Relacionamentos
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'applications')
                    ->withPivot('status', 'motivation', 'created_at', 'updated_at')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailableForApplication($query)
    {
        $today = Carbon::today();
        return $query->where('status', 'active')
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    // Métodos auxiliares
    public function isAvailableForApplication(): bool
    {
        $today = Carbon::today();
        return $this->status === 'active' 
               && $this->start_date <= $today 
               && $this->end_date >= $today;
    }

    public function hasReachedMaxCandidates(): bool
    {
        if (!$this->max_candidates) {
            return false;
        }
        
        return $this->applications()->count() >= $this->max_candidates;
    }
}

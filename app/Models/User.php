<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'avatar', 'bio', 'is_public',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_public' => 'boolean',
        ];
    }

    public function apiKeys() { return $this->hasMany(ApiKey::class); }
    public function prompts() { return $this->hasMany(Prompt::class); }
    public function images() { return $this->hasMany(Image::class); }
    public function collections() { return $this->hasMany(Collection::class)->orderBy('sort_order'); }
}

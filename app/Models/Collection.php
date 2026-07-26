<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'cover_image_id',
        'is_public', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (Collection $c) {
            if (!$c->slug) {
                $c->slug = str($c->name)->slug();
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function images() { return $this->hasMany(Image::class); }
    public function coverImage() { return $this->belongsTo(Image::class, 'cover_image_id'); }
}

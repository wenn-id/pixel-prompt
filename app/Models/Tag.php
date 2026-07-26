<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (Tag $t) {
            if (!$t->slug) {
                $t->slug = str($t->name)->slug();
            }
        });
    }

    public function images() { return $this->belongsToMany(Image::class); }
    public function prompts() { return $this->belongsToMany(Prompt::class); }
}

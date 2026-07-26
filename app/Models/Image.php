<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Image extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'prompt_id', 'collection_id',
        'file_path', 'thumbnail_path', 'width', 'height', 'file_size',
        'provider', 'model', 'parameters', 'generation_time_ms',
        'is_public', 'is_favorite', 'share_token',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'is_public' => 'boolean',
            'is_favorite' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Image $image) {
            if (!$image->share_token) {
                $image->share_token = Str::random(48);
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function prompt() { return $this->belongsTo(Prompt::class); }
    public function collection() { return $this->belongsTo(Collection::class); }
    public function tags() { return $this->belongsToMany(Tag::class); }

    public function getUrlAttribute() { return url("/gallery/{$this->id}"); }
    public function getShareUrlAttribute() { return url("/s/{$this->share_token}"); }
}

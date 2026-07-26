<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prompt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'prompt_text', 'negative_prompt',
        'width', 'height', 'cfg_scale', 'steps', 'seed', 'style_preset',
        'provider', 'model', 'is_template',
    ];

    protected function casts(): array
    {
        return [
            'cfg_scale' => 'decimal:1',
            'is_template' => 'boolean',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function images() { return $this->hasMany(Image::class); }
    public function tags() { return $this->belongsToMany(Tag::class); }
}

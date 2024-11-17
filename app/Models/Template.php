<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Template extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'content',
        'total_reactions',
        'total_comments',
        'total_reposts',
        'category_id'
    ];

    protected $casts = [
        'total_reactions' => 'integer',
        'total_comments' => 'integer',
        'total_reposts' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

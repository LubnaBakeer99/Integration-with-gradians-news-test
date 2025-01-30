<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'favorited_type',
        'favorited_id',
    ];

    public function user(): MorphTo
    {
        return $this->belongsTo(User::class);
    }

    public function favorited(): MorphTo
    {
        return $this->morphTo();
    }
}
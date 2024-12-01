<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Source extends Model
{
    public $fillable = ['user_id', 'hash', 'url', 'content'];

    public function user(): BelongsTo|User
    {
        return $this->belongsTo(User::class);
    }
}

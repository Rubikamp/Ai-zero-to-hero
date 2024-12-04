<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    public $fillable = ['user_id', 'title'];

    public function user(): BelongsTo|User
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany|Message
    {
        return $this->hasMany(Message::class);
    }
}

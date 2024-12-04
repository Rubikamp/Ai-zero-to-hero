<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $fillable = ['conversation_id', 'sender', 'message', 'data'];

    public $casts = [
        'data'=> 'array'
    ];

    public function conversation(): BelongsTo|Conversation
    {
        return $this->belongsTo(Conversation::class);
    }
}

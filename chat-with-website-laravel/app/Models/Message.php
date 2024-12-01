<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $fillable = ['conversaion_id', 'sender', 'message', 'data'];

    public $casts = [
        'data'=> 'array'
    ];

    public function conversation(): BelongsTo|Conversaion
    {
        return $this->belongsTo(Conversaion::class);
    }
}

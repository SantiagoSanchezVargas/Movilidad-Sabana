<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JivochatMessage extends Model
{
    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_phone',
        'message',
        'channel',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'message_id',
        'chat_id',
        'user_id',
        'is_read',
    ];

    protected $table = 'message_status';
}

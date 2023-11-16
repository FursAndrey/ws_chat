<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'users',
    ];

    protected $table = 'chats';

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user', 'chat_id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    public function unreadableMessages(): HasMany
    {
        return $this->hasMany(MessageStatus::class, 'chat_id', 'id')
            ->where('user_id', '=', auth()->id())
            ->where('is_read', '=', false);
    }
}

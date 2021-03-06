<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    protected $guarded = ['id'];

    protected $hidden = ['updated_at', 'conversation_id'];
}

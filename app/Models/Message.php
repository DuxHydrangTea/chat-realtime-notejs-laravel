<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'messages';
    protected $guarded = [];

    CONST TEXT = 0;
    CONST IMAGE = 1;
    CONST VIDEO = 2;
    CONST AUDIO = 3;

    public function user(){
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver');
    }

    public function reply(){
        return $this->belongsTo(Message::class,'reply_id');
    }

    public function mediaMessages(){
        return $this->hasMany(MediaMessage::class);
    }
}

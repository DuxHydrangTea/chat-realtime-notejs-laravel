<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMessage extends Model
{
    //
    protected $table = 'media_messages';
    protected $guarded =[];

    public function message(){
        return $this->belongsTo(Message::class);
    }
}

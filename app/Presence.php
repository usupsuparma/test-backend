<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    //
    protected $fillable = [
        'name', 'user_id', 'presence_in','presence_out', 'duration'
    ];
}

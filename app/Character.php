<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model 
{

    protected $table = 'got_characters';
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany('House');
    }

}
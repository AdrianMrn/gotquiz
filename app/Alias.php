<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model 
{

    protected $table = 'aliases';
    public $timestamps = false;

    public function alias()
    {
        return $this->belongsTo('Character', 'character_id');
    }

}
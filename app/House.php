<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model 
{

    protected $table = 'got_houses';
    public $timestamps = false;

    public function house()
    {
        return $this->belongsTo('Character', 'heir', 'founder', 'currentLord');
    }

}
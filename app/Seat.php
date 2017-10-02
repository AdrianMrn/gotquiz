<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model 
{

    protected $table = 'seats';
    public $timestamps = false;

    public function seat()
    {
        return $this->belongsTo('House', 'house_id');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model 
{

    protected $table = 'seats';
    public $timestamps = false;

    protected $fillable = [
        'id', 'seat', 'house_id'
    ];

    public function seat()
    {
        return $this->belongsTo('House', 'house_id');
    }

}
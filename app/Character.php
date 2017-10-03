<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model 
{

    protected $table = 'got_characters';
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'culture', 'father', 'mother', 'spouse'
    ];

    public function character()
    {
        return $this->hasMany('House');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkGotCharactersGotHouses extends Model 
{

    protected $table = 'link_got_characters_got_houses';
    public $timestamps = false;

    public function houses()
    {
        return $this->hasMany('House', 'house_id');
    }

    public function characters()
    {
        return $this->belongsToMany('Character', 'character_id');
    }

}
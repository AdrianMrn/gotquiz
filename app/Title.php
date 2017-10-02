<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model 
{

    protected $table = 'titles';
    public $timestamps = false;

    public function title()
    {
        return $this->belongsTo('Character', 'character_id');
    }

}
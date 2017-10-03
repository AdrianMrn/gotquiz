<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model 
{

    protected $table = 'titles';
    public $timestamps = false;

    protected $fillable = [
        'id', 'title', 'character_id'
    ];

    public function title()
    {
        return $this->belongsTo('Character', 'character_id');
    }

}
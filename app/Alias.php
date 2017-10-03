<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model 
{

    protected $table = 'aliases';
    public $timestamps = false;

    protected $fillable = [
        'id', 'alias', 'character_id'
    ];

    public function alias()
    {
        return $this->belongsTo('Character', 'character_id');
    }

}
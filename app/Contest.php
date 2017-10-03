<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model 
{

    protected $table = 'contests';
    public $timestamps = true;

    public function contest()
    {
        return $this->belongsTo('User', 'winner_id');
    }

}
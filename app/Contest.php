<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Contest extends Model 
{

    use SoftDeletes;

    protected $table = 'contests';
    public $timestamps = true;

    public function contest()
    {
        return $this->belongsTo('User', 'winner_id');
    }

    protected $dates = ['deleted_at'];

}
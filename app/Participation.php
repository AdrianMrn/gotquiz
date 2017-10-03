<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model 
{

    protected $table = 'participations';
    public $timestamps = true;

    public function contests()
    {
        return $this->belongsTo('Contest', 'contest_id');
    }

    public function users()
    {
        return $this->belongsTo('User', 'user_id');
    }

}
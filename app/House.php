<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model 
{

    protected $table = 'got_houses';
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'region', 'currentLord', 'heir', 'founder', 'coatOfArms', 'words'
    ];

    public function house()
    {
        return $this->belongsTo('Character', 'heir', 'founder', 'currentLord');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * リレーション系
     */
    public function frequencies(){
     return $this->belongsToMany('App\Frequency');
    }

    public function emails(){
     return $this->belongsToMany('App\Email');
    }


    /**
    * 設定系
    */
    protected $guarded = [];

}

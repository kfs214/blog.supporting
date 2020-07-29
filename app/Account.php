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


    /**
    * 設定系
    */
    protected $guarded = [];

}

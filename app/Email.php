<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = [];

    /**
     * リレーション系
     */
    public function accounts(){
     return $this->belongsToMany('App\Account');
    }

    /**
     * アクセサ
     */
    public function getGroupsAttribute(){
      return $this->accounts()->get(['id', 'account']);
    }
}

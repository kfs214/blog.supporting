<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
  /**
   * リレーション系
   */
  public function url(){
    return $this->belongsTo('App\Url');
  }

  public function accounts(){
    return $this->hasMany('App\Account');
  }

  public function emails(){
    return $this->hasManyThrough(
      'App\Email',
      'App\Account',
      'frequency_id', // accountsテーブルの外部キー
      'group',        // emailsテーブルの外部キー
      'id',           // freqテーブルのローカルキー
      'account'       // accountsテーブルのローカルキー
    );
  }


  /**
   * 設定系
   */
  protected $guarded = [];
}

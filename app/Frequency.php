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
    return $this->belongsToMany('App\Account');
  }

  protected $with = 'url:id,url';


  /**
   * アクセサ
   */
  public function getAccountIdsAttribute(){
    return $this->accounts()->get(['account_id'])->pluck('account_id');
  }

  public function getUrlAttribute(){
    return $this->url()->first(['url'])->url;
  }

  // public function emails(){
  //   return $this->hasManyThrough(
  //     'App\Email',
  //     'App\Account',
  //     'frequency_id', // accountsテーブルの外部キー
  //     'group',        // emailsテーブルの外部キー
  //     'id',           // freqテーブルのローカルキー
  //     'account'       // accountsテーブルのローカルキー
  //   );
  // }


  /**
   * 設定系
   */
  protected $guarded = [];
}

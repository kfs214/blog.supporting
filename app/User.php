<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * リレーション系
     */
    public function urls(){
      return $this->hasMany('App\Url');
    }

    public function frequencies(){
      return $this->hasMany('App\Frequency');
    }

    public function emails(){
      return $this->hasMany('App\Email');
    }

    public function accounts(){
      return $this->hasMany('App\Account');
    }


    /**
     * アクセサ
     */
    public function getUrlsAttribute(){
      return $this->urls()->get(['id', 'url']);
    }

    public function getGroupsAttribute(){
      return $this->accounts()->where('type', 'email')->get(['account'])->pluck('account');
    }


    /**
     * 元からあった系
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    // boot 方法在用户模型类完成初始化后进行加载
    // 对事件的监听应放在 boot 中
    public static function boot() {
        parent::boot();

        // 监听创建前的事件
        static::creating(function($user) {
            $user->activation_token = str_random(30);
        });

        // 监听创建后的事件
        // static::create(function() {

        // });
    }

    public function gravatar($size = '100') {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function statuses() {
        return $this->hasMany(Status::class);
    }

    public function feed() {
        return $this->statuses()->orderBy('created_at', 'desc');
    }
}

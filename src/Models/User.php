<?php

namespace CoreCMF\Core\Models;

use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,EntrustUserTrait,Notifiable;

    public $table = 'core_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasGroup($group)
    {
      if (is_array($group)) {
          foreach ($group as $groupName) {
              if ($this->hasGroup($groupName)) {
                  return true;
              }
          }
      } else {
          foreach ($this->cachedRoles() as $role) {
              if ($role->group == $group) {
                  return true;
              }
          }
      }
      return false;
    }
    /**
     * [uploads 关联Upload模型]
     * @return   [type]                   [description]
     */
    public function userInfos()
    {
        return $this->hasOne(UserInfo::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    /**
     * [findForUser 根据用户名或者邮箱、手机找到用户信息]
     */
    public function findForUser($username){
        return $this->where('name', $username)
                    ->orwhere('email',$username)
                    ->orwhere('mobile',$username)
                    ->first();
    }
    public function check($request){
        if ($request->name) {
            $user = $this->findForUser($request->name);
            $callback = '用户名已存在!';
        }
        if ($request->email) {
            $user = $this->findForUser($request->email);
            $callback = '用户邮箱已存在!';
        }
        if ($request->mobile) {
            $user = $this->findForUser($request->mobile);
            $callback = '用户手机已存在!';
        }
        if ($user) {
          if ($user->id != $request->id) {
            //resolve use Illuminate\Container\Container;
            return resolve('builderHtml')
                      ->withCode(422)
                      ->callback($callback)
                      ->response();
          }
        }
        return;
    }
}

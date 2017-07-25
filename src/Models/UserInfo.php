<?php

namespace CoreCMF\core\Models;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    public $table = 'core_user_infos';

    protected $fillable = [
        'avatar', 'integral', 'money'
    ];
    public $timestamps = false;

    protected $appends = ['avatarUrl'];
	/**
	 * [getAvatarUrlAttribute 根据ID获取头像图片URL]
	 * @param    [type]                   $value [头像图片ID]
	 * @return   [type]                          [description]
	 */
    public function getAvatarUrlAttribute()
    {
    	return $this->attributes['avatar'];
    }
}

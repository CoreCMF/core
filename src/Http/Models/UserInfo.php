<?php

namespace CoreCMF\Core\Http\Models;
use Illuminate\Database\Eloquent\Model;
use CoreCMF\Core\Http\Models\Upload;

class UserInfo extends Model
{
    public $table = 'core_user_infos';

    protected $fillable = [
        'avatar', 'integral'
    ];
    protected $guarded = ['money'];

    public $timestamps = false;

    protected $appends = ['avatarUrl'];

	/**
	 * [getAvatarUrlAttribute 根据ID获取头像图片URL]
	 * @param    [type]                   $value [头像图片ID]
	 * @return   [type]                          [description]
	 */
    public function getAvatarUrlAttribute()
    {
      $uploadModel = new Upload();
      return asset($uploadModel->getUploadWhereFirst($this->attributes['avatar'])->url);
    }
}

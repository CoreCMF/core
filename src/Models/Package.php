<?php

namespace CoreCMF\Core\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $table = 'core_packages';

    protected $fillable = ['name', 'title', 'description', 'author', 'version', 'serviceProvider','uninstall'];
    /**
     * [checkName 检查模块是否存在]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function checkName($name)
    {
        return $this->where('name', $name)->first();
    }
    /**
     * [providers 获取服务提供者类文件]
     * hasTable 检查数据表是否存在--否则安装的时候会报错
     * @return [type] [description]
     */
    public function providers()
    {
        if (Schema::hasTable('core_packages')) {
            $Packages = $this->where('status',1)->get();
            return $Packages->map(function ($package) {
                return $package->serviceProvider;
            })->toArray();
        }else{
            return [];
        }
    }
}

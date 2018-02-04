<?php

namespace CoreCMF\Core\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $table = 'core_packages';

    protected $fillable = ['name', 'title', 'description', 'author', 'version', 'providers','aliases','install','uninstall'];

    public $statusConfig= [
        'uninstall' => [
            'type' => 'danger',
            'icon' => 'fa fa-trash',
            'title' => '未安装'
        ],
        'open'  => [
            'type' => 'success',
            'icon' => 'fa fa-check',
            'title' => '开启'
        ],
        'close'  => [
            'type' => 'warning',
            'icon' => 'fa fa-power-off',
            'title' => '关闭'
        ],
    ];
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
            $packages = $this->where('status', 'open')->get();
            return $packages->map(function ($package) {
                return json_decode($package->providers, true);
            })->collapse()->toArray();
        } else {
            return [];
        }
    }
    /**
     * [aliases 获取门面类注册文件]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-29
     */
    public function aliases()
    {
        if (Schema::hasTable('core_packages')) {
            $packages = $this->where('status', 'open')->get();
            return $packages->map(function ($package) {
                return json_decode($package->aliases, true);
            })->collapse()->toArray();
        } else {
            return [];
        }
    }
}

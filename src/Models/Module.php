<?php

namespace CoreCMF\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $table = 'core_modules';

    protected $fillable = ['name', 'title', 'description', 'author', 'version', 'serviceProvider'];
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
     * @return [type] [description]
     */
    public function providers()
    {
        $Modules = $this->all();
        return $Modules->map(function ($module) {
            return $module->serviceProvider;
        })->toArray();
    }
}

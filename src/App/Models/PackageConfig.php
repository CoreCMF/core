<?php

namespace CoreCMF\Core\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class PackageConfig extends Model
{
    public $table = 'core_package_configs';

    protected $fillable = ['name', 'key', 'value', 'status'];
    /**
     * 返回格式化后的value
     */
    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }
    /**
     * 卸载包
     */
    public function uninstallPackage($name){
        return $this->where('name', $name)->forceDelete();
    }
}

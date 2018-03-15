<?php

namespace CoreCMF\Core\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class PackageConfig extends Model
{
    public $table = 'core_package_configs';

    protected $fillable = ['name', 'key', 'value', 'status'];

    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }
}

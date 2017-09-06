<?php

namespace CoreCMF\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $table = 'core_modules';

    protected $fillable = ['name', 'title', 'description', 'author', 'version', 'serviceProvider'];

    public function checkName($name)
    {
        return $this->where('name', $name)->first();
    }

}

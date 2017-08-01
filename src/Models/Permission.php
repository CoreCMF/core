<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public $table = 'entrust_roles';

    protected $fillable = ['name', 'display_name', 'description'];

    public function isExist($name)
    {
        return ($this->where('name', $name)->count() != 0) ? true : false;
    }
}

<?php
namespace CoreCMF\Core\App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $fillable = ['name', 'parent', 'display_name', 'description', 'group'];

    public function isExist($name)
    {
        return ($this->where('name', $name)->count() != 0) ? true : false;
    }
}

<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public $table = 'entrust_roles';
}

<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public $table = 'entrust_roles';
    
    protected $fillable = ['name', 'display_name', 'description'];
}

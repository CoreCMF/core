<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'parent', 'display_name', 'description', 'group'];
}

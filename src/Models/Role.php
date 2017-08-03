<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'display_name', 'description', 'group'];
}

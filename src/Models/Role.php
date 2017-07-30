<?php
namespace CoreCMF\core\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public $table = 'entrust_permissions';
    
    protected $fillable = ['name', 'display_name', 'description'];
}

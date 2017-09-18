<?php
namespace CoreCMF\Core\Http\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = ['name', 'display_name', 'description', 'group'];
    /**
     * 关联权限id
     */
    public function permissionId($id){
        $roles = $this->find($id)->load('perms');//关联权限数据
        return $roles->perms->map(function ($item, $key) {
            return $item->id;
        });
    }

}

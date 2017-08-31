<?php
namespace CoreCMF\Core\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entrust_roles')->insert([
            'name'              => 'user',
            'display_name'      => '普通用户',
            'description'       => '注册后普通用户',
            'group'             => 'global'
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class CoreUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('core_users')->insert([
            'name' 	    => 'admin',
            'email' 	=> 'bigrocs@qq.com',
            'mobile' 	=> '18816161818',
            'password' 	=> bcrypt('admin888'),
            'status'  => 1
        ]);
    }
}

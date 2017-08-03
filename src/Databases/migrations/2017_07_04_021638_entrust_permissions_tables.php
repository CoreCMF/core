<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing permissions
        Schema::create('entrust_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('权限名字');
            $table->string('parent')->nullable()->comment('父级权限名字');
            $table->string('display_name')->nullable()->comment('显示权限名称');
            $table->string('description')->nullable()->comment('权限描述');
            $table->string('group')->nullable()->comment('权限分组');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('entrust_permissions');
    }
}

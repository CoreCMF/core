<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorePackageConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_package_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)             ->comment('扩展包名称')->unique();
            $table->string('key', 128)             ->comment('配置键值');
            $table->json('value')                  ->comment('配置内容')->nullable();
            $table->string('status', 16)           ->comment('状态')->default('close');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_packages');
    }
}

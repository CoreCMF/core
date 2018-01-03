<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)             ->comment('标识')->unique();
            $table->string('title', 64)            ->comment('名称');
            $table->text('description')           ->comment('描述')->nullable();
            $table->string('author', 32)           ->comment('作者')->nullable();
            $table->string('version', 8)           ->comment('版本')->nullable();
            $table->string('provider', 255)        ->comment('服务提供者')->nullable();
            $table->string('uninstall', 64)        ->comment('卸载artisan')->nullable();
            $table->tinyInteger('status')         ->comment('状态')->default(1);
            $table->bigInteger('sort')            ->comment('排序')->unsigned()->default(0);
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

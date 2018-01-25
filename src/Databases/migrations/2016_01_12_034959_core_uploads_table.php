<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoreUploadsTable extends Migration
{

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('core_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('uid')           ->comment('用户ID')->default(0)->unsigned();
            $table->string('name')              ->comment('文件名称');
            $table->string('path')              ->comment('文件路径');
            $table->char('extension', 6)         ->comment('文件类型');
            $table->bigInteger('size')          ->comment('文件大小')->unsigned();
            $table->char('md5', 32)              ->comment('文件MD5');
            $table->char('sha1', 40)             ->comment('文件SHA1编码');
            $table->string('disk', 15)         ->comment('文件存储驱动');
            $table->bigInteger('download')      ->comment('文件下载次数')->unsigned();
            $table->string('status',16)       ->comment('状态')->default('open');
            $table->bigInteger('sort')          ->comment('排序')->unsigned();
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
        Schema::dropIfExists('core_uploads');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 1024)->comment('图片名称');
            $table->string('desc', 1024)->comment('描述');
            $table->string('path_type')->comment('存储方式');
            $table->string('path', 1024)->comment('访问路径');
            $table->string('original_path', 2048)->comment('原始路径');
            $table->string('source')->comment('图片来源');
            $table->char('md5', 32)->comment('文件md5')->index();
            $table->char('sha1', 40)->comment('文件sha1')->index();
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
        Schema::dropIfExists('images');
    }
}

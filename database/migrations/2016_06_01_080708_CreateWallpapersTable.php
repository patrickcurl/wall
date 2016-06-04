<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallpapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('source')->nullable();
            $table->integer('resolution')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->string('image_url')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('views')->default(0);
            $table->enum('purity', ['safe', 'sketchy', 'nsfw']);
            $table->date('upload_date')->nullable();
            $table->timestamps();
        });
        // title, source, resolution, thumbnail_url, image_url, purity, size, views, upload_date
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wallpapers');
    }
}

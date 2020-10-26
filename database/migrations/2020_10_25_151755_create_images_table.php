<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
            $table->string('original_name', 191);
            $table->string('file_name', 191);
            $table->boolean('resized')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('image_copies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned()->nullable(false);
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->string('file_name', 191);
            $table->string('url', 191);
            $table->string('image_type', 10);
            $table->string('size_type', 20);
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
        Schema::dropIfExists('image_copies');
        Schema::dropIfExists('images');
    }
}

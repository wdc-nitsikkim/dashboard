<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomepageNotiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['announcement', 'notice', 'download', 'tender'])->nullable(false);
            $table->string('display_text', 255)->nullable(false);
            $table->string('link', 255);
            $table->enum('status', [0, 1])->default(1)->nullable(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homepage_notifications');
    }
}

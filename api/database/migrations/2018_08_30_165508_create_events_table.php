<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('is_viewed')->unsigned()->default(0);
            $table->tinyInteger('is_hidden')->unsigned()->default(0);
            $table->tinyInteger('is_sent')->unsigned()->default(0);
            $table->string('message');
            $table->string('link');
            $table->timestamp('fired_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

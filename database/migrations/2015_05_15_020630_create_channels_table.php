<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->string('name');
            $table->string('public', 20);
            $table->enum('refreshes', ['yes', 'no']);
            $table->integer('max_connections')->default(0);
            $table->integer('active_users')->default(0);
            $table->integer('events_fired')->default(0);
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
        Schema::drop('channels');
    }
}

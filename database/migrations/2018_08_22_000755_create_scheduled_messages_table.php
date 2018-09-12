<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group_id')->nullable();
            $table->string('from');
            $table->string('to')->nullable();
            $table->integer('recipients');
            $table->text('message');
            $table->integer('msg_count')->unsigned();
            $table->integer('characters')->unsigned();
            $table->string('cost');
            $table->string('status')->default('Scheduled');
            $table->dateTime('send_time');
            $table->timestamps();

            // index
            $table->index('user_id');

            // Relationship
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_messages');
    }
}

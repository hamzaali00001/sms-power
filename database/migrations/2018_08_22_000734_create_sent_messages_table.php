<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('from');
            $table->string('to');
            $table->text('message');
            $table->integer('msg_count')->unsigned();
            $table->integer('characters')->unsigned();
            $table->string('cost');
            $table->string('status')->default('Submitted');
            $table->timestamps();

            // index
            $table->index('user_id');

            // Relationship
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_messages');
    }
}

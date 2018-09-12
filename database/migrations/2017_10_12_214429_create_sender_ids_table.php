<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSenderIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sender_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('cost');
            $table->string('trans_code')->nullable();
            $table->string('status')->default('Processing');
            $table->timestamps();

            // indexes
            $table->index(['user_id', 'slug']);

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
        Schema::dropIfExists('sender_ids');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group_id');
            $table->string('filename')->nullable();
            $table->string('extension')->nullable();
            $table->string('filesize')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            // index
            $table->index('user_id');
            $table->index('group_id');

            // Relationships
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
        Schema::dropIfExists('file_uploads');
    }
}

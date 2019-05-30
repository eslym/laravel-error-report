<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('error_id');
            $table->string('email')
                ->nullable();
            $table->longText('content');
            $table->timestamps();
            $table->foreign('error_id')
                ->references('id')->on('error_records')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_comments');
    }
}

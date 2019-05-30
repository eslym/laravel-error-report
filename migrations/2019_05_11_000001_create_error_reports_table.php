<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('error_id');
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
        Schema::dropIfExists('error_reports');
    }
}

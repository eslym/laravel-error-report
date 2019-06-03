<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_records', function (Blueprint $table) {
            $table->uuid('id')
                ->primary();
            $table->string('class');
            $table->string('site');
            $table->text('hash');
            $table->boolean('is_console');
            $table->unsignedInteger('counter')
                ->default(0);
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
        Schema::dropIfExists('error_records');
    }
}

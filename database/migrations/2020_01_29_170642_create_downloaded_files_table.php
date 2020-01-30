<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloaded_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('url');
            $table->string('filename')->nullable();
            $table->string('percent')->default(0);
            $table->string('eta')->default('n/a');
            $table->string('speed')->default('n/a');
            $table->string('size')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_gubbed')->default(false);
            $table->string('error_message')->nullable();
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
        Schema::dropIfExists('downloaded_files');
    }
}

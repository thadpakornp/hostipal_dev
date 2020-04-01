<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('charts_desc_id')->unsigned();
            $table->foreign('charts_desc_id')->references('id')->on('charts_description')->onUpdate('cascade')->onDelete('cascade');
            $table->text('files');
            $table->text('type_files');
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('charts_files');
    }
}

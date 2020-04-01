<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts_description', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('charts_id')->unsigned();
            $table->foreign('charts_id')->references('id')->on('charts')->onUpdate('cascade')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->bigInteger('add_by_user')->unsigned();
            $table->foreign('add_by_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('g_location_lat')->nullable();
            $table->string('g_location_long')->nullable();
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
        Schema::dropIfExists('charts_description');
    }
}

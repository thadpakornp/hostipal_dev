<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix_id',5);
            $table->string('name');
            $table->string('surname')->nullable();
            $table->enum('sex',['Male','Female'])->nullable();
            $table->string('hn')->nullable();
            $table->bigInteger('add_by_user')->unsigned();
            $table->foreign('add_by_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status',['Activate','Deactivate']);
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
        Schema::dropIfExists('charts');
    }
}

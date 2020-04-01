<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name',150);
            $table->string('phone',10);
            $table->string('address',100);
            $table->string('district',100);
            $table->string('county',100);
            $table->string('province',100);
            $table->string('code',5);
            $table->string('g_location_lat');
            $table->string('g_location_long');
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
        Schema::dropIfExists('offices');
    }
}

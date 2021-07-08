<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string("title", 50);
            $table->string("description", 200);
            $table->integer("external_id")->nullable();
            $table->enum("charge_period", array('1', '30', '60', '90', '365'));
            $table->decimal("value");
            $table->integer("max_usage");
            $table->integer("max_bots");
            $table->boolean("balance_saving");

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
        Schema::dropIfExists('plans');
    }
}

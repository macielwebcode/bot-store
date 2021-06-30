<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extracts', function (Blueprint $table) {
            $table->id();
            $table->decimal("balance");
            $table->decimal("old_balance");
            $table->enum('operation', [ 'C', 'D' ]);
            $table->string("description", 100);
            $table->timestamps();

            $table->foreignId('user_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("extracts", function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('extracts');
    }
}

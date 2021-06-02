<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("description", 200);
            $table->string("image", 200);
            $table->decimal("value");
            $table->decimal("simulated_value");
            $table->integer("scale_quantity");
            $table->boolean("layout_active");
            // $table->string("token_test", 200);  // decidir se vai ter próprio token
            $table->json("payload_structure")->nullable();
            $table->json("payload_sample")->nullable();

            $table->timestamps();

            $table->foreignId('category_id')->constrained()->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("products", function(Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('products');
    }
}

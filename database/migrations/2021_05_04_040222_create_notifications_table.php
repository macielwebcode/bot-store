<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string("text", 200);
            $table->boolean("is_read");
            $table->boolean("is_notified");

            $table->timestamps();
            $table->index("user_id");
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
        Schema::table("notifications", function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('notifications');
    }
}

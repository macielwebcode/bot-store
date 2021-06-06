<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            $table->decimal('balance');
            $table->string('status', 50);
            $table->string('cpf', 15);
            $table->string('cnpj', 18)->nullable();
            $table->string('token_key', 255)->nullable();
            $table->string('token_pass', 255)->nullable();
            $table->string('profile_pic', 100)->nullable();

            // Address
            $table->string('street', 200)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('country', 2)->nullable();
            

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

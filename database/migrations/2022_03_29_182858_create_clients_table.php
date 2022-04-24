<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('cpf', 14)->unique();
            $table->string('phone',13);
            $table->string('email', 100)->unique();
            $table->string('zip_code', 9)->nullable();
            $table->string('address', 250);
            $table->string('number', 5);
            $table->string('complement', 200)->nullable();
            $table->string('district', 50);
            $table->string('city',50);
            $table->string('state', 2);
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
        Schema::dropIfExists('clients');
    }
};

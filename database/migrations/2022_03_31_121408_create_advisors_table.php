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
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->unique();
            $table->string('phone',13);
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('zip_code')->nullable();
            $table->string('address',250);
            $table->string('number',5);
            $table->string('district',50);
            $table->string('city',50);
            $table->string('state',2);
            $table->string('complement',200)->nullable();
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
        Schema::dropIfExists('advisors');
    }
};

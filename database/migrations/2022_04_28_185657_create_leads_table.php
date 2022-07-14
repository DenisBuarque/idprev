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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('phone',50);
            $table->string('email',100)->nullable();
            $table->char('zip_code',9)->nullable();
            $table->string('address')->nullable();
            $table->string('number',5)->nullable();
            $table->string('district',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',2)->nullable();
            $table->char('tag',1); //etiqueta
            $table->string('process',50)->nullable();
            $table->char('situation',1);
            $table->double('financial',8,2)->nullable();
            $table->string('action',50)->nullable();
            $table->string('court',50)->nullable(); //tribunal
            $table->string('stick',50)->nullable(); //vara
            $table->string('responsible')->nullable();
            $table->date('date_fulfilled')->nullable();
            $table->text('greeting')->nullable();
            $table->boolean('confirmed')->nullable();

            $table->unsignedBigInteger('user_id')->nullable()->default(NULL);
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('leads');
    }
};

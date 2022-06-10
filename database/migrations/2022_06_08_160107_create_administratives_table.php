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
        Schema::create('administratives', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('phone',50);
            $table->string('rg',30);
            $table->string('cpf',16);
            $table->string('cep',9)->nullable();
            $table->string('address',255);
            $table->string('number',5);
            $table->string('district',50);
            $table->string('city',50);
            $table->char('state',2);
            $table->string('email',100)->nullable();
            $table->string('inss',50);
            $table->string('situation',255);
            $table->text('requirements')->nullable(); // exigencias
            $table->char('results',1);
            $table->text('benefits');
            $table->date('initial_date');
            $table->date('concessao_date');
            $table->double('fees',8,2);
            $table->text('payment');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('administratives');
    }
};

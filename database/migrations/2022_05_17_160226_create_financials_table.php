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
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->string('precatory',50);
            $table->date('receipt_date');
            $table->integer('bank');
            $table->double('value_total',8,2);
            $table->string('value_client');
            $table->string('fees');
            $table->char('fees_received');
            $table->date('payday');
            $table->double('payment_amount',8,2);
            $table->char('payment_bank',2);
            $table->string('confirmation_date');
            $table->string('people');
            $table->string('contact');
            $table->char('payment_confirmation',1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
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
        Schema::dropIfExists('financials');
    }
};

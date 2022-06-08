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
            $table->char('payment_confirmation',1);
            $table->string('precatory',50)->nullable();
            $table->date('receipt_date')->nullable();
            $table->integer('bank')->nullable();
            $table->double('value_total',8,2)->nullable();
            $table->string('value_client')->nullable();
            $table->string('fees')->nullable();
            $table->char('fees_received')->nullable();
            $table->char('installments',3)->nullable();
            $table->date('payday')->nullable();
            $table->double('payment_amount',8,2)->nullable();
            $table->char('payment_bank',2)->nullable();
            $table->string('confirmation_date')->nullable();
            $table->string('people')->nullable();
            $table->string('contact')->nullable();
            $table->text('comments')->nullable();
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

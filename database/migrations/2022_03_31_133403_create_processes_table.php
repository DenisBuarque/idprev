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
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('folder');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->string('title',250);
            $table->integer('tag'); //etiqueta
            $table->integer('instance');
            $table->string('number_process',50);
            $table->string('juizo');
            $table->string('vara');
            $table->string('foro');
            $table->string('action');
            $table->integer('days');
            $table->text('description');
            $table->decimal('valor_causa',10,2);
            $table->string('data');
            $table->decimal('valor_condenacao',10,2);
            $table->text('detail');
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
        Schema::dropIfExists('processes');
    }
};

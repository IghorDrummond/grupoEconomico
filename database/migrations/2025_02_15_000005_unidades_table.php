<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("unidades", function (Blueprint $table){
            $table->id();//Cria campo id auto incrementado
            $table->string("nome_fantasia", 100);
            $table->string("razao_social", 100);
            $table->string("cnpj", 20);
            $table->unsignedBigInteger('bandeira');
            $table->boolean('active')->default(true)->nullable();
            $table->timestamps();

            //Chave Primarias
            $table->foreign('bandeira')->references('id')->on('bandeiras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("unidades");
    }
};

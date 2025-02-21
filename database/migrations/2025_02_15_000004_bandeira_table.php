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
        Schema::create("bandeiras", function (Blueprint $table){
            $table->id();//Cria o campo id auto incrementado
            $table->string("nome", 100); //Cria o campo Nome Fantasia
            $table->boolean('active')->default(true)->nullable();
            $table->unsignedBigInteger("grupo_economico");
            $table->timestamps();//Cria os campos created_at e updated_at

            //Chave primária
            $table->foreign('grupo_economico')->references('id')->on('grupo_economicos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("bandeiras");
    }
};

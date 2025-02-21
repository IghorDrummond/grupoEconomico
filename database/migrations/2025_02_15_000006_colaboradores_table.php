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
        Schema::create("colaboradores", function (Blueprint $table){
            $table->id("id");
            $table->string("nome", 100);
            $table->string("email", 150);
            $table->string("cpf",14);
            $table->unsignedBigInteger("unidade");
            $table->boolean('active')->default(true)->nullable();
            $table->timestamps();

            //Chave primária
            $table->foreign("unidade")->references("id")->on("unidades");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("colaboradores");
    }
};

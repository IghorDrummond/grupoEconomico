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
        Schema::create("registro", function (Blueprint $table){
            $table->id("id");
            $table->unsignedBigInteger("user_id");
            $table->string("operacao", 20);
            $table->string("tabela_alterada", 50);
            $table->text("dado_anterior")->nullable();
            $table->text("dado_atual")->nullable();
            $table->timestamps();

            //Chave primária
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("registro");
    }
};

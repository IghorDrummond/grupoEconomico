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
        Schema::create("grupo_economicos", function (Blueprint $table){
            $table->id();//Cria campo id incrimentado
            $table->string("nome", 100)->default("");//Cria campo Nome
            $table->boolean("active")->default(true)->nullable();
            $table->timestamps();//Cria campo created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("grupo_economicos");
    }
};

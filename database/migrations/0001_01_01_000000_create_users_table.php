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

        //Cria tabela de tipo de usuários
        Schema::create("type_users", function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->timeStamps();
        });

        //Cria tabela de usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("lastname");
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->dateTime('birth');
            $table->string('cpf', 11);
            $table->unsignedBigInteger('type_user')->default(1)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('type_user')->references('id')->on('type_users');
        });

        //Cria tabela de recuperação de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
            $table->boolean('actived')->default(true)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

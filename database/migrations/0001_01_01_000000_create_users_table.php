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

        try {
            Schema::table('bibliotecas', function (Blueprint $table) {
                $table->dateTime('updated_at', 0)->nullable();
                $table->dateTime('created_at', 0)->nullable();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->string('api_token', 80)
                    ->unique()
                    ->nullable()
                    ->default(null);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('users');
        //Schema::dropIfExists('password_reset_tokens');
        //Schema::dropIfExists('sessions');
    }
};

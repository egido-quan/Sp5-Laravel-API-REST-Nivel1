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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();

            $table->foreignId('player1_user_id')->constrained('players', 'user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('player2_user_id')->constrained('players', 'user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->json('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

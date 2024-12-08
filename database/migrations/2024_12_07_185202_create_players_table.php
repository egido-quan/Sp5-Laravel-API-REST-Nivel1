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
        Schema::create('players', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->primary();
            $table->integer('ranking');
            $table->integer('height');
            $table->enum('playing_hand', ['left', 'right']);
            $table->enum('backhand_style', ['one hand', 'two hands', 'both hands']);
            $table->text('briefing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

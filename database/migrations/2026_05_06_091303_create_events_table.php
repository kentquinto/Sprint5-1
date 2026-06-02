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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string ('title', 45);
            $table->string ('description', 2000);
            $table->string ('location', 45)->nullable();
            $table->decimal ('entry_fee', 8, 2)->default(0);
            $table->integer ('max_players');
            $table->dateTime('date_time');
            $table->enum('status', ["upcoming", "ongoing", "finished", "cancelled"])->default('upcoming');
            $table->foreignId ('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId ('game_id')->constrained('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

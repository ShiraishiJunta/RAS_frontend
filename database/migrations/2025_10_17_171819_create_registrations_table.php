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
    Schema::create('registrations', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel 'users' (untuk relawan)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Menghubungkan ke tabel 'events'
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        $table->timestamps();

        // Mencegah satu user mendaftar di event yang sama lebih dari sekali
        $table->unique(['user_id', 'event_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

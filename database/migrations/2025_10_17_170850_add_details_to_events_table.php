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
        // Kita menggunakan Schema::table() untuk mengubah tabel yang sudah ada
        Schema::table('events', function (Blueprint $table) {
            // Menambahkan kolom 'description' setelah kolom 'location'
            $table->text('description')->nullable()->after('location');

            // Menambahkan kolom 'photo' untuk menyimpan path/nama file gambar
            $table->string('photo')->nullable()->after('description');

            // Menambahkan kolom 'volunteers_needed' untuk jumlah relawan yang dibutuhkan
            $table->integer('volunteers_needed')->default(0)->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Method 'down' ini akan dijalankan jika kita melakukan rollback migrasi
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['description', 'photo', 'volunteers_needed']);
        });
    }
};
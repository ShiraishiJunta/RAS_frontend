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
        // Hapus foreign key constraint dulu jika ada (nama default: registrations_user_id_foreign, registrations_event_id_foreign)
        // Cek nama constraint di database Anda jika berbeda
        Schema::table('registrations', function (Blueprint $table) {
             if (Schema::hasColumn('registrations', 'user_id')) {
                 try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
             }
             if (Schema::hasColumn('registrations', 'event_id')) {
                 try { $table->dropForeign(['event_id']); } catch (\Exception $e) {}
             }
        });
        Schema::dropIfExists('registrations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika di rollback, buat kembali tabelnya (sesuaikan dengan migrasi lama Anda)
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'event_id']);
        });
    }
};

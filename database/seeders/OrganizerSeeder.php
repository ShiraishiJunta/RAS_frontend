<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizers')->insert([
            [
                'name' => 'Yayasan Cipta Bangsa',
                'email' => 'admin@ciptabangsa.org',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komunitas Peduli Lingkungan',
                'email' => 'info@pedulilingkungan.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gerakan Sehat Indonesia',
                'email' => 'contact@sehatindonesia.id',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Relawan Pendidikan Anak',
                'email' => 'relawan.pendidikan@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Green Future Project',
                'email' => 'hello@greenfuture.dev',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
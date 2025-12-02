<?php

namespace Database\Seeders;

use App\Models\Volunteer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizerSeeder::class,
            UserSeeder::class,
            EventSeeder::class,
            // VolunteerSeeder::class,  
            // Hapus baris ini: RegistrationSeeder::class,
            // Mungkin tambahkan VolunteerSeeder jika perlu data dummy pendaftar tamu
            // VolunteerSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel events.
     */
    public function run(): void
    {
        // Pastikan ada organizer sebelum insert event
        $organizerIds = DB::table('organizers')->pluck('id');

        // Jika tidak ada organizer, buat dummy satu (untuk mencegah error)
        if ($organizerIds->isEmpty()) {
            $organizerId = DB::table('organizers')->insertGetId([
                'name' => 'Organisasi Perintis',
                'email' => 'organizer@example.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $organizerIds = collect([$organizerId]);
        }

        $events = [
            [
                'title' => 'Donasi Buku untuk Sekolah Terpencil',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(5),
                'time' => '08:00 - 12:00',
                'location' => 'SD Negeri 2 Mojorejo, Madiun',
                'description' => 'Kegiatan sosial untuk mengumpulkan buku bacaan bagi anak-anak di daerah terpencil. Relawan membantu sortir dan distribusi buku.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 25,
                'contact_phone' => '08123450001',
                'contact_email' => 'donasibuku@example.com',
            ],
            [
                'title' => 'Penanaman Pohon di Taman Kota',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(7),
                'time' => '07:00 - 10:00',
                'location' => 'Taman Hijau Demangan',
                'description' => 'Aksi nyata menghijaukan kota dengan menanam 100 bibit pohon trembesi dan mangga.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 50,
                'contact_phone' => '08123450002',
                'contact_email' => 'gogreen@example.com',
            ],
            [
                'title' => 'Pemeriksaan Kesehatan Gratis Lansia',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(10),
                'time' => '08:00 - 13:00',
                'location' => 'Balai Desa Kanigoro',
                'description' => 'Layanan cek tensi, gula darah, dan konsultasi dokter umum gratis untuk warga lansia prasejahtera.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 15,
                'contact_phone' => '08123450003',
                'contact_email' => 'sehatbersama@example.com',
            ],
            [
                'title' => 'Bersih-Bersih Sungai Madiun',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(14),
                'time' => '06:30 - 11:00',
                'location' => 'Bantaran Kali Madiun',
                'description' => 'Gotong royong membersihkan sampah plastik di sepanjang aliran sungai untuk mencegah banjir.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 100,
                'contact_phone' => '08123450004',
                'contact_email' => 'sungaibebassampah@example.com',
            ],
            [
                'title' => 'Mengajar Bahasa Inggris Dasar',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(3),
                'time' => '15:00 - 17:00',
                'location' => 'Panti Asuhan Siti Hajar',
                'description' => 'Kelas sore ceria mengenalkan kosakata Bahasa Inggris dasar lewat lagu dan permainan.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 5,
                'contact_phone' => '08123450005',
                'contact_email' => 'englishfun@example.com',
            ],
            [
                'title' => 'Pelatihan Dasar Pertolongan Pertama',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(20),
                'time' => '09:00 - 12:00',
                'location' => 'Gedung Serbaguna Madiun',
                'description' => 'Pelatihan dasar pertolongan pertama untuk masyarakat umum, bekerja sama dengan PMI.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 20,
                'contact_phone' => '08123450014',
                'contact_email' => 'ppdasar@example.com',
            ],
            [
                'title' => 'Kelas Menanam Sayur Hidroponik',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(30),
                'time' => '09:00 - 14:00',
                'location' => 'Kebun Edukasi Kota Madiun',
                'description' => 'Pelatihan menanam sayuran hidroponik sederhana untuk masyarakat perkotaan.',
                'photo' => null, // <--- UBAH JADI NULL
                'volunteers_needed' => 18,
                'contact_phone' => '08123450015',
                'contact_email' => 'hidroponik@example.com',
            ],
        ];

        foreach ($events as $event) {
            $event['organizer_id'] = $organizerIds->random();
            $event['created_at'] = now();
            $event['updated_at'] = now();
            DB::table('events')->insert($event);
        }
    }
}
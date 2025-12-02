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
        $organizerIds = DB::table('organizers')->pluck('id');

        $events = [
            [
                'title' => 'Donasi Buku untuk Sekolah Terpencil',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(5),
                'time' => '08:00 - 12:00',
                'location' => 'SD Negeri 2 Mojorejo, Madiun',
                'description' => 'Kegiatan sosial untuk mengumpulkan buku bacaan bagi anak-anak di daerah terpencil. Relawan membantu sortir dan distribusi buku.',
                'photo' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 25,
                'contact_phone' => '08123450001',
                'contact_email' => 'donasibuku@example.com',
            ],
            [
                'title' => 'Penanaman Pohon di Taman Kota',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(7),
                'time' => '07:00 - 10:00',
                'location' => 'Taman Sumber Wangi, Madiun',
                'description' => 'Aksi penghijauan kota dengan menanam 500 bibit pohon. Relawan membantu penanaman dan penyiraman awal.',
                'photo' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 40,
                'contact_phone' => '08123450002',
                'contact_email' => 'greenmadiun@example.com',
            ],
            [
                'title' => 'Donor Darah Peduli Sesama',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(9),
                'time' => '09:00 - 13:00',
                'location' => 'RSUD Dr. Soedono, Madiun',
                'description' => 'Aksi donor darah rutin bekerja sama dengan PMI Madiun untuk membantu stok darah rumah sakit.',
                'photo' => 'https://images.unsplash.com/photo-1627856014753-4d4e5e4659f1?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 30,
                'contact_phone' => '08123450003',
                'contact_email' => 'donordarah@example.com',
            ],
            [
                'title' => 'Bimbingan Belajar Gratis Anak Panti',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(10),
                'time' => '13:00 - 16:00',
                'location' => 'Panti Asuhan Nurul Hidayah, Madiun',
                'description' => 'Relawan mengajar mata pelajaran dasar dan membantu anak panti mempersiapkan ujian sekolah.',
                'photo' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 20,
                'contact_phone' => '08123450004',
                'contact_email' => 'bimbelgratis@example.com',
            ],
            [
                'title' => 'Pembersihan Sungai Brantas',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(12),
                'time' => '07:00 - 11:00',
                'location' => 'Sungai Brantas, Madiun',
                'description' => 'Aksi bersih-bersih sungai untuk menjaga ekosistem dan mencegah penumpukan sampah.',
                'photo' => 'https://images.unsplash.com/photo-1562088287-bde35a1ea917?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 35,
                'contact_phone' => '08123450005',
                'contact_email' => 'bersihsungai@example.com',
            ],
            [
                'title' => 'Pemeriksaan Kesehatan Gratis Lansia',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(14),
                'time' => '08:00 - 12:00',
                'location' => 'Balai Kelurahan Kartoharjo, Madiun',
                'description' => 'Pemeriksaan tekanan darah, gula darah, dan kolesterol secara gratis untuk warga lanjut usia.',
                'photo' => 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 15,
                'contact_phone' => '08123450006',
                'contact_email' => 'lansiasehat@example.com',
            ],
            [
                'title' => 'Workshop Daur Ulang Sampah Plastik',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(16),
                'time' => '09:00 - 14:00',
                'location' => 'Balai RW 05, Taman, Madiun',
                'description' => 'Pelatihan membuat kerajinan dari sampah plastik seperti tas dan pot bunga.',
                'photo' => 'https://images.unsplash.com/photo-1616401784845-180882ba9baa?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 20,
                'contact_phone' => '08123450007',
                'contact_email' => 'daurulang@example.com',
            ],
            [
                'title' => 'Kelas Literasi Digital Remaja',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(18),
                'time' => '10:00 - 15:00',
                'location' => 'SMAN 3 Madiun',
                'description' => 'Pelatihan tentang penggunaan media sosial secara bijak, aman, dan produktif bagi pelajar.',
                'photo' => 'https://images.unsplash.com/photo-1584697964191-6ebd9b52d78a?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 18,
                'contact_phone' => '08123450008',
                'contact_email' => 'literasidigital@example.com',
            ],
            [
                'title' => 'Senam Sehat Bersama Warga',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(20),
                'time' => '06:30 - 09:00',
                'location' => 'Lapangan Gulun, Madiun',
                'description' => 'Senam pagi bersama instruktur profesional untuk meningkatkan kesehatan dan kebersamaan warga.',
                'photo' => 'https://images.unsplash.com/photo-1605296867304-46d5465a13f1?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 12,
                'contact_phone' => '08123450009',
                'contact_email' => 'senamsehat@example.com',
            ],
            [
                'title' => 'Edukasi Pengelolaan Sampah Sekolah',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(22),
                'time' => '09:00 - 11:00',
                'location' => 'SMPN 4 Madiun',
                'description' => 'Program edukasi pengelolaan sampah dan pelatihan bank sampah di lingkungan sekolah.',
                'photo' => 'https://images.unsplash.com/photo-1581579186983-10c6b0b7b5d8?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 22,
                'contact_phone' => '08123450010',
                'contact_email' => 'edukasilingkungan@example.com',
            ],
            [
                'title' => 'Pemberian Vitamin Anak Sekolah Dasar',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(23),
                'time' => '08:00 - 11:00',
                'location' => 'SDN 1 Madiun Lor',
                'description' => 'Pemberian vitamin dan penyuluhan kesehatan gigi bagi siswa sekolah dasar.',
                'photo' => 'https://images.unsplash.com/photo-1576765607924-bb90e6eaf8d1?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 28,
                'contact_phone' => '08123450011',
                'contact_email' => 'vitaminanak@example.com',
            ],
            [
                'title' => 'Pelatihan Menulis untuk Guru Relawan',
                'category' => 'Pendidikan',
                'date' => Carbon::now()->addDays(25),
                'time' => '09:00 - 13:00',
                'location' => 'Perpustakaan Kota Madiun',
                'description' => 'Workshop menulis kreatif dan literasi untuk para guru relawan.',
                'photo' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 16,
                'contact_phone' => '08123450012',
                'contact_email' => 'menuliskreatif@example.com',
            ],
            [
                'title' => 'Gerakan Bersih Pasar Tradisional',
                'category' => 'Lingkungan',
                'date' => Carbon::now()->addDays(27),
                'time' => '07:00 - 11:30',
                'location' => 'Pasar Sleko, Madiun',
                'description' => 'Membersihkan area pasar dan memberikan edukasi kepada pedagang mengenai pengelolaan sampah.',
                'photo' => 'https://images.unsplash.com/photo-1596113922037-8efc6af0f9d0?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 26,
                'contact_phone' => '08123450013',
                'contact_email' => 'bersihpasar@example.com',
            ],
            [
                'title' => 'Pelatihan Pertolongan Pertama Dasar',
                'category' => 'Kesehatan',
                'date' => Carbon::now()->addDays(29),
                'time' => '08:00 - 12:00',
                'location' => 'Gedung Serbaguna Madiun',
                'description' => 'Pelatihan dasar pertolongan pertama untuk masyarakat umum, bekerja sama dengan PMI.',
                'photo' => 'https://images.unsplash.com/photo-1617802691857-8f650f3d2771?auto=format&fit=crop&w=800&q=80',
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
                'photo' => 'https://images.unsplash.com/photo-1588768989216-274f8b9a6a4e?auto=format&fit=crop&w=800&q=80',
                'volunteers_needed' => 18,
                'contact_phone' => '08123450015',
                'contact_email' => 'hidroponik@example.com',
            ],
        ];

        // Masukkan ke database dengan organizer_id acak
        foreach ($events as $event) {
            DB::table('events')->insert([
                ...$event,
                'organizer_id' => $organizerIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

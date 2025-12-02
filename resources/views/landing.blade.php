@extends('layouts.app')

@section('content')
    <!-- HERO SECTION -->
    <section class="bg-blue-50 py-16 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Bergabunglah dengan Komunitas Relawan</h2>
            <p class="text-lg text-gray-600 mb-8">
                Temukan kegiatan sosial dan kemanusiaan di seluruh Indonesia. Jadilah bagian dari perubahan positif untuk dunia
                yang lebih baik.
            </p>
            {{-- --- PERUBAHAN DI SINI --- --}}
            <a href="{{ route('organizer.register') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-all">Gabung
               Sekarang</a>
            {{-- --- AKHIR PERUBAHAN --- --}}
        </div>
    </section>

    <!-- FITUR UTAMA -->
    <section id="fitur" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-10">Fitur Utama</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                 <div class="p-8 bg-gray-50 rounded-2xl shadow-sm hover:shadow-md transition">
                     <i data-lucide="heart-handshake" class="w-12 h-12 mx-auto text-blue-600 mb-4"></i>
                     <h4 class="text-xl font-semibold mb-2">Kegiatan Sosial</h4>
                     <p class="text-gray-600">Temukan berbagai kegiatan sosial dari berbagai organisasi di seluruh daerah.</p>
                 </div>
                 <div class="p-8 bg-gray-50 rounded-2xl shadow-sm hover:shadow-md transition">
                     <i data-lucide="users" class="w-12 h-12 mx-auto text-green-600 mb-4"></i>
                     <h4 class="text-xl font-semibold mb-2">Komunitas Relawan</h4>
                     <p class="text-gray-600">Bergabunglah dalam komunitas relawan dan kolaborasi untuk dampak yang lebih besar.
                     </p>
                 </div>
                 <div class="p-8 bg-gray-50 rounded-2xl shadow-sm hover:shadow-md transition">
                     <i data-lucide="calendar" class="w-12 h-12 mx-auto text-purple-600 mb-4"></i>
                     <h4 class="text-xl font-semibold mb-2">Manajemen Event</h4>
                     <p class="text-gray-600">Atur dan promosikan kegiatan sosial Anda dengan sistem yang mudah digunakan.</p>
                 </div>
             </div>
        </div>
    </section>

    <!-- STATISTIK -->
    <section class="bg-blue-600 py-16 text-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-10">Bersama Kita Berdampak Nyata</h3>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                 <div>
                     <h4 class="text-5xl font-bold mb-2">120+</h4>
                     <p class="text-blue-100">Kegiatan Sosial</p>
                 </div>
                 <div>
                     <h4 class="text-5xl font-bold mb-2">500+</h4>
                     <p class="text-blue-100">Relawan Aktif</p>
                 </div>
                 <div>
                     <h4 class="text-5xl font-bold mb-2">80+</h4>
                     <p class="text-blue-100">Organisasi Terdaftar</p>
                 </div>
             </div>
        </div>
    </section>
@endsection
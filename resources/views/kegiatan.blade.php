@extends('layouts.app')

@section('content')
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
      <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 text-sm font-medium"
        x-cloak>
        {{ session('success') }}
      </div>
    @endif

    {{-- Hero --}}
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-gray-900 mb-4">Temukan Kesempatan Berbagi</h2>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Jelajahi berbagai kegiatan sosial dan kemanusiaan. Jadilah relawan dan berikan dampak positif.
      </p>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-gray-100">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
          <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
          <input id="search" type="text" placeholder="Cari kegiatan sosial..."
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
        </div>
        <div class="flex items-center space-x-2">
          <i data-lucide="filter" class="text-gray-400 w-5 h-5"></i>
          <select id="categoryFilter"
            class="border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            <option value="all">Semua Kategori</option>
            <option value="Pendidikan">Pendidikan</option>
            <option value="Lingkungan">Lingkungan</option>
            <option value="Kesehatan">Kesehatan</option>
          </select>
        </div>
      </div>
    </div>

    {{-- Grid Kegiatan --}}
    <div id="eventGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {{-- Loading State (Spinner sederhana agar tidak kosong saat loading) --}}
      <div class="col-span-full text-center py-12 text-gray-500">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
        <p>Memuat kegiatan...</p>
      </div>
    </div>

    {{-- Jika Tidak Ada --}}
    <div id="noResult" class="hidden text-center py-12">
      <div class="text-gray-400 mb-4">
        <i data-lucide="search-x" class="w-16 h-16 mx-auto"></i>
      </div>
      <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada kegiatan ditemukan</h3>
      <p class="text-gray-600 text-sm">Coba ubah kata kunci pencarian atau filter kategori.</p>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const eventGrid = document.getElementById('eventGrid');
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('categoryFilter');
    const noResult = document.getElementById('noResult');

    // --- PERUBAHAN 1: Ambil URL dari .env ---
    const apiBaseUrl = "{{ env('API_URL') }}"; 
    
    let events = []; // Simpan data asli

    async function fetchEvents() {
      try {
        // --- PERUBAHAN 2: Gunakan URL Dinamis & Endpoint yang benar (/events) ---
        // Jika API_URL di .env sudah ada /api, maka cukup tambah /events
        const res = await fetch(`${apiBaseUrl}/events`); 
        
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }

        // --- PERUBAHAN 3: Ambil data dari properti .data ---
        // Karena controller mengembalikan: { message: "...", data: [...] }
        const responseJson = await res.json();
        events = responseJson.data; 

        console.log("Data diterima:", events);
        renderEvents(events);
      } catch (error) {
        console.error("Gagal mengambil data kegiatan:", error);
        eventGrid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-red-500 mb-2">Gagal memuat data.</p><button onclick="fetchEvents()" class="text-blue-600 hover:underline text-sm">Coba Lagi</button></div>';
        noResult.classList.add('hidden');
      }
    }

    function formatDate(dateString) {
      if (!dateString) return 'Tanggal tidak tersedia';
      try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Format tanggal salah';
        return date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
      } catch (e) {
        return 'Format tanggal salah';
      }
    }

    function renderEvents(list) {
      eventGrid.innerHTML = ''; // Kosongkan grid
      
      if (!list || list.length === 0) {
        noResult.classList.remove('hidden');
        return;
      }
      noResult.classList.add('hidden');

      list.forEach(ev => {
        const div = document.createElement('div');
        div.className = 'bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col';

        // Cek foto
        const imageUrl = ev.photo ? `/storage/${ev.photo}` : 'https://placehold.co/800x600/e0e0e0/7c7c7c?text=Kegiatan';
        const category = ev.category || 'Umum';
        const title = ev.title || 'Judul Tidak Tersedia';
        const description = ev.description || 'Deskripsi tidak tersedia.';
        const date = formatDate(ev.date);
        const time = ev.time || 'Waktu tidak tersedia';
        const location = ev.location || 'Lokasi tidak tersedia';
        const organizerName = ev.organizer ? ev.organizer.name : 'Penyelenggara tidak diketahui';
        const volunteerCount = ev.volunteers_count !== undefined ? ev.volunteers_count : 0;
        const volunteersNeeded = ev.volunteers_needed || 0;
        const eventId = ev.id;

        // Badge warna kategori
        let badgeColor = 'bg-gray-100 text-gray-700';
        if(category === 'Pendidikan') badgeColor = 'bg-blue-100 text-blue-700';
        else if(category === 'Lingkungan') badgeColor = 'bg-green-100 text-green-700';
        else if(category === 'Kesehatan') badgeColor = 'bg-purple-100 text-purple-700';

        div.innerHTML = `
            <img src="${imageUrl}" alt="${title}" class="w-full h-48 object-cover">
            <div class="p-6 flex flex-col flex-grow">
                <span class="px-3 py-1 rounded-full text-xs font-medium mb-2 self-start ${badgeColor}">${category}</span>
                <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">${title}</h3>
                <p class="text-gray-600 mb-3 text-sm line-clamp-2 flex-grow">${description}</p>
                
                <div class="space-y-1 mb-3 text-gray-600 text-xs border-t pt-3">
                    <div class="flex items-center"><i data-lucide="calendar" class="inline w-3 h-3 mr-2 opacity-70"></i>${date}</div>
                    <div class="flex items-center"><i data-lucide="clock" class="inline w-3 h-3 mr-2 opacity-70"></i>${time}</div>
                    <div class="flex items-center"><i data-lucide="map-pin" class="inline w-3 h-3 mr-2 opacity-70"></i>${location}</div>
                    <div class="flex items-center"><i data-lucide="users" class="inline w-3 h-3 mr-2 opacity-70"></i>${volunteerCount}/${volunteersNeeded} relawan</div>
                </div>
                
                <div class="flex justify-between items-center border-t pt-3 mt-auto">
                    <div class="text-xs font-medium text-gray-700">Oleh: ${organizerName}</div>
                    <a href="/volunteer/register/${eventId}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">Saya Tertarik</a>
                </div>
            </div>`;
        eventGrid.appendChild(div);
      });

      // Re-init icon
      if(window.lucide) lucide.createIcons();
    }

    function filterEvents() {
      const keyword = searchInput.value.toLowerCase().trim();
      const category = categoryFilter.value;

      const filtered = events.filter(ev => {
        const titleMatch = ev.title ? ev.title.toLowerCase().includes(keyword) : false;
        const descMatch = ev.description ? ev.description.toLowerCase().includes(keyword) : false;
        const orgMatch = ev.organizer && ev.organizer.name ? ev.organizer.name.toLowerCase().includes(keyword) : false;
        const categoryMatch = category === 'all' || (ev.category && ev.category === category);

        return (titleMatch || descMatch || orgMatch) && categoryMatch;
      });
      renderEvents(filtered);
    }

    searchInput.addEventListener('input', filterEvents);
    categoryFilter.addEventListener('change', filterEvents);

    // Jalankan fetch saat load
    fetchEvents();

  </script>
@endpush
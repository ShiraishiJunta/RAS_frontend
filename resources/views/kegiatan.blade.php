@extends('layouts.app')

@section('content')
  {{-- Gunakan x-data untuk mengelola seluruh state halaman ini --}}
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
        x-data="publicEventManager()"
        x-init="fetchEvents()">

    {{-- Notifikasi Sukses (Session PHP) --}}
    @if(session('success'))
      <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 text-sm font-medium"
        x-cloak>
        {{ session('success') }}
      </div>
    @endif

    {{-- Hero Section --}}
    <div class="text-center mb-16">
      <span class="text-blue-600 font-semibold tracking-wider uppercase text-sm">Aksi Nyata</span>
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mt-2 mb-4">Temukan Panggilan Jiwamu</h2>
      <p class="text-xl text-gray-500 max-w-2xl mx-auto">
        Jelajahi ribuan kegiatan sosial di sekitarmu. Satu langkah kecil darimu, sejuta harapan bagi mereka.
      </p>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-2 mb-10 max-w-4xl mx-auto sticky top-24 z-30">
      <div class="flex flex-col md:flex-row gap-2">
        <div class="flex-1 relative">
          <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
          <input type="text" x-model="search" placeholder="Cari kegiatan (misal: Mengajar, Bersih Pantai)..."
            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white border focus:border-blue-500 rounded-xl focus:ring-0 text-gray-800 transition-colors">
        </div>
        <div class="flex items-center md:w-1/3">
          <div class="relative w-full">
              <i data-lucide="filter" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
              <select x-model="category"
                class="w-full pl-12 pr-10 py-3 bg-gray-50 border-transparent focus:bg-white border focus:border-blue-500 rounded-xl focus:ring-0 text-gray-800 appearance-none cursor-pointer transition-colors">
                <option value="all">Semua Kategori</option>
                <option value="Pendidikan">Pendidikan</option>
                <option value="Lingkungan">Lingkungan</option>
                <option value="Kesehatan">Kesehatan</option>
              </select>
              <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- Loading State --}}
    <div x-show="isLoading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-100 border-t-blue-600"></div>
        <p class="mt-4 text-gray-500 animate-pulse">Sedang memuat kegiatan terbaik...</p>
    </div>

    {{-- Grid Kegiatan --}}
    <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      
      <template x-for="event in filteredEvents" :key="event.id">
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full hover:-translate-y-1">
            
            {{-- Gambar --}}
            <div class="relative h-52 overflow-hidden bg-gray-100">
                <img :src="event.photo ? `/storage/${event.photo}` : 'https://placehold.co/800x600/f3f4f6/9ca3af?text=Kegiatan'" 
                     :alt="event.title" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                {{-- Badge Kategori --}}
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-md"
                          :class="{
                              'bg-blue-100/90 text-blue-700': event.category === 'Pendidikan',
                              'bg-green-100/90 text-green-700': event.category === 'Lingkungan',
                              'bg-purple-100/90 text-purple-700': event.category === 'Kesehatan',
                              'bg-gray-100/90 text-gray-700': !['Pendidikan','Lingkungan','Kesehatan'].includes(event.category)
                          }" x-text="event.category"></span>
                </div>
            </div>

            {{-- Konten Card --}}
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors" x-text="event.title"></h3>
                
                <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-grow" x-text="event.description"></p>

                {{-- Info Singkat --}}
                <div class="space-y-2 mb-5 text-sm text-gray-600 border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                        <span x-text="formatDate(event.date)"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i>
                        <span class="truncate" x-text="event.location"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-green-500"></i>
                        <span><span class="font-semibold" x-text="event.volunteers_count || 0"></span> / <span x-text="event.volunteers_needed"></span> Relawan</span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-3 mt-auto">
                    <button @click="openDetail(event)" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors text-sm">
                        Lihat Detail
                    </button>
                    <a :href="`/volunteer/register/${event.id}`" class="flex-1 px-4 py-2 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition-all shadow-md hover:shadow-lg text-center text-sm">
                        Gabung
                    </a>
                </div>
            </div>
        </div>
      </template>

    </div>

    {{-- State Kosong --}}
    <div x-show="!isLoading && filteredEvents.length === 0" class="text-center py-20" x-cloak>
        <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="search-x" class="w-10 h-10 text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada kegiatan ditemukan</h3>
        <p class="text-gray-500">Coba ubah kata kunci atau kategori pencarian Anda.</p>
        <button @click="resetFilter()" class="mt-4 text-blue-600 font-medium hover:underline">Reset Filter</button>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL DETAIL KEGIATAN (PUBLIK) --}}
    {{-- ========================================== --}}
    <div x-show="detailModalOpen" 
         class="fixed inset-0 z-[60] overflow-y-auto" 
         x-cloak>
        
        <div x-show="detailModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm"
             @click="closeDetail()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="detailModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <div class="overflow-y-auto custom-scrollbar flex-grow">
                    <div class="relative h-64 md:h-80 w-full bg-gray-200">
                        <img :src="selectedEvent.photo ? `/storage/${selectedEvent.photo}` : 'https://placehold.co/800x600/f3f4f6/9ca3af?text=Kegiatan'" 
                             class="w-full h-full object-cover">
                        
                        <button @click="closeDetail()" class="absolute top-4 right-4 bg-white/20 backdrop-blur-md hover:bg-white/40 p-2 rounded-full text-white transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>

                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 pt-20">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-600 text-white mb-3 inline-block" x-text="selectedEvent.category"></span>
                            <h2 class="text-2xl md:text-3xl font-bold text-white leading-tight" x-text="selectedEvent.title"></h2>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-2 space-y-6">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                                        <i data-lucide="align-left" class="w-5 h-5 text-blue-600"></i> Deskripsi
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed whitespace-pre-line" x-text="selectedEvent.description"></p>
                                </div>

                                <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                                    <h4 class="font-bold text-blue-900 mb-3 text-sm uppercase tracking-wide">Diselenggarakan Oleh</h4>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center text-blue-700 font-bold">
                                            <i data-lucide="building-2" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900" x-text="selectedEvent.organizer ? selectedEvent.organizer.name : 'Organisasi'"></p>
                                            <p class="text-xs text-gray-500">Terverifikasi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-4">
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tanggal</span>
                                        <div class="flex items-center gap-2 text-gray-900 font-medium mt-1">
                                            <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                                            <span x-text="formatDate(selectedEvent.date)"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Waktu</span>
                                        <div class="flex items-center gap-2 text-gray-900 font-medium mt-1">
                                            <i data-lucide="clock" class="w-4 h-4 text-orange-500"></i>
                                            <span x-text="selectedEvent.time"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Lokasi</span>
                                        <div class="flex items-start gap-2 text-gray-900 font-medium mt-1">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                                            <span x-text="selectedEvent.location"></span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-500">Kuota Relawan</span>
                                        <span class="font-bold text-gray-900"><span x-text="selectedEvent.volunteers_count || 0"></span>/<span x-text="selectedEvent.volunteers_needed"></span></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" 
                                             :style="`width: ${Math.min(100, ((selectedEvent.volunteers_count || 0) / selectedEvent.volunteers_needed) * 100)}%`">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1 text-right" x-show="(selectedEvent.volunteers_needed - (selectedEvent.volunteers_count || 0)) > 0">
                                        Tersisa <span x-text="Math.max(0, selectedEvent.volunteers_needed - (selectedEvent.volunteers_count || 0))"></span> slot lagi!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 flex-shrink-0">
                    <button @click="closeDetail()" class="px-6 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                    <a :href="`/volunteer/register/${selectedEvent.id}`" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span>Daftar Sekarang</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

  </main>
@endsection

@push('scripts')
<script>
    const apiBaseUrl = "{{ env('API_URL') }}"; 

    function publicEventManager() {
        return {
            events: [],
            isLoading: true,
            search: '',
            category: 'all',
            
            // State Modal
            detailModalOpen: false,
            selectedEvent: {}, // Objek kosong untuk menampung event yang dipilih

            // --- FETCH DATA ---
            async fetchEvents() {
                try {
                    const res = await fetch(`${apiBaseUrl}/events`);
                    if (!res.ok) throw new Error('Gagal mengambil data');
                    const json = await res.json();
                    this.events = json.data;
                } catch (error) {
                    console.error("Error:", error);
                } finally {
                    this.isLoading = false;
                    // Re-init icon setelah data diload (tunggu render alpine)
                    setTimeout(() => lucide.createIcons(), 100);
                }
            },

            // --- FILTERING (COMPUTED) ---
            get filteredEvents() {
                const q = this.search.toLowerCase().trim();
                return this.events.filter(ev => {
                    const matchSearch = (ev.title && ev.title.toLowerCase().includes(q)) || 
                                        (ev.description && ev.description.toLowerCase().includes(q)) ||
                                        (ev.organizer && ev.organizer.name.toLowerCase().includes(q));
                    
                    const matchCategory = this.category === 'all' || ev.category === this.category;
                    
                    return matchSearch && matchCategory;
                });
            },

            resetFilter() {
                this.search = '';
                this.category = 'all';
            },

            // --- MODAL LOGIC ---
            openDetail(event) {
                this.selectedEvent = event;
                this.detailModalOpen = true;
                document.body.style.overflow = 'hidden'; // Disable scroll body
                setTimeout(() => lucide.createIcons(), 50); // Render icon di modal
            },

            closeDetail() {
                this.detailModalOpen = false;
                document.body.style.overflow = ''; // Enable scroll body
                setTimeout(() => this.selectedEvent = {}, 300); // Clear data setelah animasi tutup
            },

            // --- FORMATTER ---
            formatDate(dateStr) {
                if (!dateStr) return '-';
                try {
                    const date = new Date(dateStr);
                    if (isNaN(date.getTime())) return dateStr;
                    return date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                } catch (e) { return dateStr; }
            }
        }
    }
</script>
@endpush
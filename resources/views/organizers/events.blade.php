@extends('layouts.app')

@push('styles')
<style>
    .modal-content-area { overflow-y: auto; padding-bottom: 1rem; }
    [x-cloak] { display: none !important; }
    @keyframes shake { 0%, 100% { transform: translateX(0); } 10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); } 20%, 40%, 60%, 80% { transform: translateX(5px); } }
    .shake-anim { animation: shake 0.5s ease-in-out; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8"
     x-data="eventsApiManager()"
     x-init="initData()">

    {{-- Notifikasi --}}
    <div x-show="notification.show" x-transition 
         :class="notification.isError ? 'bg-red-500' : 'bg-green-500'"
         class="fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-[60] text-sm font-medium" 
         x-cloak>
        <span x-text="notification.message"></span>
    </div>

    {{-- Header --}}
    <div class="flex items-center mb-6">
         <a href="{{ url('/') }}" class="text-gray-500 hover:text-blue-600 mr-4 flex items-center" title="Kembali ke Beranda"><i data-lucide="arrow-left" class="w-6 h-6"></i></a>
         <h1 class="text-3xl font-bold text-gray-800">Manajemen <span class="text-blue-600">Kegiatan</span></h1>
    </div>

    {{-- Tombol Tambah --}}
    <div class="flex justify-between items-center mb-4">
         <h2 class="text-lg font-semibold text-gray-700">Daftar Kegiatan Anda</h2>
         <button @click="openAddModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-1 font-medium text-sm transition duration-150 ease-in-out shadow-sm hover:shadow">
            <i data-lucide="plus" class="w-5 h-5"></i><span>Tambah Kegiatan</span>
         </button>
    </div>

    {{-- Loading State --}}
    <div x-show="isLoading" class="text-center py-12" x-cloak>
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
        <p class="text-gray-500">Memuat data kegiatan...</p>
    </div>

    {{-- Tabel Events (API Version) --}}
    <div class="bg-white shadow rounded-lg overflow-x-auto border border-gray-200" x-show="!isLoading">
        <table class="min-w-full border-collapse align-top">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider min-w-[60px]">Foto</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider min-w-[150px]">Judul</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider min-w-[160px]">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider">Lokasi</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider">Relawan</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider min-w-[100px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <template x-for="(event, index) in events" :key="event.id">
                    <tr class="hover:bg-gray-50 text-sm text-gray-800">
                        <td class="px-4 py-3" x-text="index + 1"></td>
                        <td class="px-4 py-3">
                            <img :src="event.photo ? `/storage/${event.photo}` : 'https://placehold.co/80x60/e2e8f0/64748b?text=N/A'" 
                                 class="w-16 h-12 rounded object-cover border bg-gray-100">
                        </td>
                        <td class="px-4 py-3 font-medium" x-text="event.title"></td>
                        <td class="px-4 py-3" x-text="event.category"></td>
                        <td class="px-4 py-3" x-text="formatDateDisplay(event.date)"></td>
                        <td class="px-4 py-3" x-text="event.location"></td>
                        <td class="px-4 py-3">
                            <span x-text="event.volunteers_count"></span> / <span x-text="event.volunteers_needed"></span>
                        </td>
                        <td class="px-4 py-3 flex space-x-1 items-center">
                             <button @click="openDetailModal(event)" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-100 transition duration-150" title="Detail">
                                 <i data-lucide="info" class="w-4 h-4"></i>
                             </button>
                             <button @click="openEditModal(event)" class="text-yellow-500 hover:text-yellow-700 p-1 rounded hover:bg-yellow-100 transition duration-150" title="Edit">
                                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                              </button>
                             <button @click="openDeleteModal(event)" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-100 transition duration-150" title="Hapus">
                                 <i data-lucide="trash-2" class="w-4 h-4"></i>
                             </button>
                        </td>
                    </tr>
                </template>
                
                <tr x-show="events.length === 0">
                    <td colspan="8" class="text-center py-10 text-gray-500">
                        <div class="flex flex-col items-center">
                            <i data-lucide="calendar-x" class="w-12 h-12 text-gray-400 mb-2"></i>
                            <span>Anda belum membuat kegiatan apapun.</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- MODAL TAMBAH (Menggunakan variabel addModalOpen) --}}
    <div x-show="addModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl flex flex-col" style="max-height: 90vh;" @click.away="closeAddModal()">
            <div class="px-6 py-4 border-b flex justify-between items-center flex-shrink-0 rounded-t-xl">
                <h2 class="text-xl font-bold text-gray-800">Tambah Kegiatan Baru</h2>
                <button @click="closeAddModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
            </div>
            
            <div class="modal-content-area px-6 pt-6 flex-grow">
                <form @submit.prevent="submitForm('add')">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Judul Kegiatan *</label><input type="text" x-model="formData.title" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Kategori *</label><select x-model="formData.category" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required><option value="Pendidikan">Pendidikan</option><option value="Lingkungan">Lingkungan</option><option value="Kesehatan">Kesehatan</option></select></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Tanggal *</label><input type="date" x-model="formData.date" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Waktu *</label><input type="text" x-model="formData.time" placeholder="09:00 - 12:00" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Lokasi *</label><input type="text" x-model="formData.location" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Volunteer Dibutuhkan *</label><input type="number" x-model="formData.volunteers_needed" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required min="1"></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Kontak Telepon *</label><input type="text" x-model="formData.contact_phone" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Email Penyelenggara *</label><input type="email" x-model="formData.contact_email" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></div>
                        <div class="md:col-span-2"><label class="font-semibold block mb-1 text-sm text-gray-700">Foto (Opsional)</label><input type="file" @change="handleFileChange" class="w-full border rounded-lg p-2 text-sm"></div>
                        <div class="md:col-span-2"><label class="font-semibold block mb-1 text-sm text-gray-700">Deskripsi *</label><textarea x-model="formData.description" rows="4" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required></textarea></div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4 border-t sticky bottom-0 bg-white">
                        <button type="button" @click="closeAddModal()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Simpan Kegiatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT (Menggunakan variabel editModalOpen & editData) --}}
    <div x-show="editModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl flex flex-col" style="max-height: 90vh;" @click.away="closeEditModal()">
            <div class="px-6 py-4 border-b flex justify-between items-center flex-shrink-0"><h2 class="text-xl font-bold text-gray-800">Edit Kegiatan</h2><button @click="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button></div>
            <div class="modal-content-area px-6 pt-6 flex-grow">
                <form @submit.prevent="submitForm('edit')">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                        {{-- Field sama persis dengan Add, tapi binding ke editData --}}
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Judul Kegiatan *</label><input type="text" x-model="editData.title" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Kategori *</label><select x-model="editData.category" class="w-full border rounded-lg p-2 text-sm" required><option value="Pendidikan">Pendidikan</option><option value="Lingkungan">Lingkungan</option><option value="Kesehatan">Kesehatan</option></select></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Tanggal *</label><input type="date" x-model="editData.date" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Waktu *</label><input type="text" x-model="editData.time" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Lokasi *</label><input type="text" x-model="editData.location" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Volunteer Dibutuhkan *</label><input type="number" x-model="editData.volunteers_needed" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Kontak Telepon *</label><input type="text" x-model="editData.contact_phone" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Email Penyelenggara *</label><input type="email" x-model="editData.contact_email" class="w-full border rounded-lg p-2 text-sm" required></div>
                        <div class="md:col-span-2"><label class="font-semibold block mb-1 text-sm text-gray-700">Ganti Foto (Opsional)</label><input type="file" @change="handleFileChange" class="w-full border rounded-lg p-2 text-sm"></div>
                        <div class="md:col-span-2"><label class="font-semibold block mb-1 text-sm text-gray-700">Deskripsi *</label><textarea x-model="editData.description" rows="4" class="w-full border rounded-lg p-2 text-sm" required></textarea></div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4 border-t sticky bottom-0 bg-white">
                        <button type="button" @click="closeEditModal()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL (Menggunakan variabel detailModalOpen & detailData) --}}
    <div x-show="detailModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl flex flex-col relative" style="max-height: 90vh;" @click.away="closeDetailModal()">
             <div class="px-6 py-4 border-b flex justify-between items-center flex-shrink-0">
                <h2 class="text-xl font-bold text-gray-800">Detail & Analitik Kegiatan</h2>
                <button @click="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            <div class="modal-content-area p-6 space-y-5 text-sm flex-grow">
                {{-- Info Utama --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div class="md:col-span-1">
                        <img :src="detailData.photo ? `/storage/${detailData.photo}` : 'https://placehold.co/800x600?text=No+Img'" class="w-full h-auto rounded-lg object-cover border p-1 bg-gray-100 aspect-[4/3]">
                    </div>
                    <div class="md:col-span-2 space-y-1.5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2" x-text="detailData.title"></h3>
                        <p><strong class="text-gray-500 w-24 inline-block">Kategori:</strong> <span x-text="detailData.category" class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs"></span></p>
                        <p><strong class="text-gray-500 w-24 inline-block">Tanggal:</strong> <span x-text="formatDateDisplay(detailData.date)"></span></p>
                        <p><strong class="text-gray-500 w-24 inline-block">Lokasi:</strong> <span x-text="detailData.location"></span></p>
                    </div>
                </div>
                {{-- Analitik --}}
                <div class="border-t pt-4">
                     <h4 class="font-semibold text-gray-600 mb-3">Analitik Relawan</h4>
                     <div class="mb-4">
                         <div class="flex justify-between text-xs text-gray-500 mb-1">
                             <span>Progress</span>
                             <span x-text="`${detailData.volunteers_count} / ${detailData.volunteers_needed} (${detailData.progressPercentage}%)`"></span>
                         </div>
                         <div class="w-full bg-gray-200 rounded-full h-2.5"><div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${detailData.progressPercentage}%`"></div></div>
                     </div>
                </div>
                {{-- Daftar Relawan --}}
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-600 mb-3">Daftar Pendaftar</h4>
                    <div class="max-h-60 overflow-y-auto border rounded-lg bg-gray-50">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white text-xs">
                                <template x-for="vol in detailData.volunteers" :key="vol.id">
                                    <tr>
                                        <td class="px-3 py-2" x-text="vol.name"></td>
                                        <td class="px-3 py-2" x-text="vol.email"></td>
                                        <td class="px-3 py-2" x-text="vol.phone"></td>
                                    </tr>
                                </template>
                                <tr x-show="!detailData.volunteers || detailData.volunteers.length === 0">
                                    <td colspan="3" class="text-center py-4 text-gray-500">Belum ada pendaftar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t sticky bottom-0 bg-white">
                <button @click="closeDetailModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Tutup</button>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div x-show="deleteModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 text-center" @click.away="closeDeleteModal()">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Kegiatan?</h3>
            <p class="text-sm text-gray-500 mb-6">Yakin ingin menghapus "<strong x-text="deleteEventTitle"></strong>"?</p>
            <div class="flex justify-center gap-4">
                <button @click="closeDeleteModal()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg text-sm">Batal</button>
                <button @click="submitDeleteForm()" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function eventsApiManager() {
        return {
            events: [],
            isLoading: true,
            token: localStorage.getItem('auth_token'),
            
            // Status Modal (Sesuai dengan HTML Anda)
            addModalOpen: false, 
            editModalOpen: false, 
            detailModalOpen: false, 
            deleteModalOpen: false,

            // Data Object (Sesuai dengan HTML Anda)
            formData: { title: '', category: 'Pendidikan', date: '', time: '', location: '', volunteers_needed: 1, contact_phone: '', contact_email: '', description: '', photo: null },
            editData: { id: null, title: '', category: '', date: '', time: '', location: '', volunteers_needed: 1, contact_phone: '', contact_email: '', description: '', photo: null },
            detailData: { title: '', category: '', date: '', location: '', photo: null, volunteers_needed: 0, volunteers_count: 0, volunteers: [], progressPercentage: 0 },
            
            // Delete Data
            deleteEventId: null, 
            deleteEventTitle: '',

            notification: { show: false, message: '', isError: false },

            initData() {
                if (!this.token) { window.location.href = '/organizer/login'; return; }
                this.fetchEvents();
                setTimeout(() => lucide.createIcons(), 500);
            },

            async fetchEvents() {
                this.isLoading = true;
                try {
                    const res = await fetch('/api/my-events', { headers: { 'Authorization': `Bearer ${this.token}` } });
                    if (res.status === 401) window.location.href = '/organizer/login';
                    const json = await res.json();
                    this.events = json.data;
                } catch (e) { this.showNotify('Gagal memuat data', true); }
                finally { this.isLoading = false; setTimeout(() => lucide.createIcons(), 100); }
            },

            // --- FUNGSI MODAL (Sesuai dengan HTML Anda) ---
            openAddModal() { 
                this.formData = { title: '', category: 'Pendidikan', date: '', time: '', location: '', volunteers_needed: 1, contact_phone: '', contact_email: '', description: '', photo: null };
                this.addModalOpen = true; 
            },
            closeAddModal() { this.addModalOpen = false; },

            openEditModal(event) {
                // Copy data event ke editData (Clone object agar reaktif)
                this.editData = JSON.parse(JSON.stringify(event)); 
                this.editData.photo = null; // Reset photo input
                this.editModalOpen = true;
            },
            closeEditModal() { this.editModalOpen = false; },

            async openDetailModal(event) {
                this.detailData = { ...event, volunteers: [], progressPercentage: 0 };
                this.detailModalOpen = true;
                
                // Hitung Progress Awal
                this.calculateAnalytics();

                // Fetch Volunteer Data dari API
                try {
                    const res = await fetch(`/api/events/${event.id}/volunteers`, { 
                        headers: { 'Authorization': `Bearer ${this.token}` } 
                    });
                    const json = await res.json();
                    if(res.ok) {
                        this.detailData.volunteers = json.data;
                        // Update count jika berbeda
                        this.detailData.volunteers_count = json.data.length;
                        this.calculateAnalytics();
                    }
                } catch(e) { console.error(e); }
            },
            closeDetailModal() { this.detailModalOpen = false; },

            openDeleteModal(event) {
                this.deleteEventId = event.id;
                this.deleteEventTitle = event.title;
                this.deleteModalOpen = true;
            },
            closeDeleteModal() { this.deleteModalOpen = false; },

            // --- FUNGSI SUBMIT ---
            handleFileChange(e) {
                const file = e.target.files[0];
                if (this.addModalOpen) this.formData.photo = file;
                if (this.editModalOpen) this.editData.photo = file;
            },

            // --- CREATE & UPDATE (NOTIFIKASI POJOK KANAN DENGAN ALASAN) ---
            async submitForm(type) {
                const isEdit = type === 'edit';
                const data = isEdit ? this.editData : this.formData;
                const url = isEdit ? `/api/events/${data.id}` : '/api/events';
                
                const payload = new FormData();
                payload.append('title', data.title);
                payload.append('category', data.category);
                payload.append('date', data.date);
                payload.append('time', data.time);
                payload.append('location', data.location);
                payload.append('volunteers_needed', data.volunteers_needed);
                payload.append('contact_phone', data.contact_phone);
                payload.append('contact_email', data.contact_email);
                payload.append('description', data.description);
                
                if (data.photo instanceof File) {
                    payload.append('photo', data.photo);
                }
                
                if (isEdit) payload.append('_method', 'POST'); 

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 
                            'Authorization': `Bearer ${this.token}`,
                            'Accept': 'application/json' 
                        },
                        body: payload
                    });
                    
                    const json = await res.json();

                    // --- CEK ERROR ---
                    if (!res.ok) {
                        let errorMessage = 'Gagal menyimpan data.'; // Pesan default

                        if (res.status === 422) {
                            // Error Validasi (Misal: Foto Kebesaran / Email Salah)
                            // Format JSON: { "photo": ["The photo field must not be greater than..."] }
                            const errorKeys = Object.keys(json);
                            if (errorKeys.length > 0) {
                                const firstField = errorKeys[0];
                                const firstMsg = json[firstField][0];
                                
                                // Terjemahkan pesan umum jika perlu (Opsional)
                                if(firstMsg.includes('not be greater than 2048')) {
                                    errorMessage = "Gagal: Ukuran foto maksimal 2MB.";
                                } else {
                                    errorMessage = `Gagal: ${firstMsg}`;
                                }
                            } else if (json.message) {
                                errorMessage = json.message;
                            }
                        } else if (json.message) {
                            errorMessage = json.message;
                        }

                        throw new Error(errorMessage);
                    }

                    // --- JIKA SUKSES ---
                    this.showNotify(isEdit ? 'Berhasil diperbarui!' : 'Berhasil dibuat!');
                    
                    isEdit ? this.closeEditModal() : this.closeAddModal();
                    this.fetchEvents();

                } catch (e) {
                    // --- TAMPILKAN ALASAN DI NOTIFIKASI POJOK KANAN ---
                    // Parameter 'true' artinya warna merah (Error)
                    this.showNotify(e.message, true);
                }
            },

            async submitDeleteForm() {
                try {
                    await fetch(`/api/events/${this.deleteEventId}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${this.token}` }
                    });
                    this.showNotify('Terhapus');
                    this.closeDeleteModal();
                    this.fetchEvents();
                } catch (e) { this.showNotify('Gagal hapus', true); }
            },

            // --- HELPER ---
            calculateAnalytics() {
                const needed = parseInt(this.detailData.volunteers_needed || 1);
                const count = parseInt(this.detailData.volunteers_count || 0);
                let pct = (count / needed) * 100;
                this.detailData.progressPercentage = Math.min(100, Math.round(pct));
            },
            formatDateDisplay(dateStr) {
                if(!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            },
            showNotify(msg, isErr = false) {
                this.notification = { show: true, message: msg, isError: isErr };
                setTimeout(() => this.notification.show = false, 3000);
            }
        }
    }
</script>
@endpush
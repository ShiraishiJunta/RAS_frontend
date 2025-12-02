@extends('layouts.app')

@push('styles')
<style>
    .modal-content-area { overflow-y: auto; padding-bottom: 1rem; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8"
     x-data="eventsApiManager()"
     x-init="initData()">

    <div x-show="notification.show" x-transition 
         :class="notification.isError ? 'bg-red-500' : 'bg-green-500'"
         class="fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-[60] text-sm font-medium" 
         x-cloak>
        <span x-text="notification.message"></span>
    </div>

    <div class="flex items-center mb-6">
         <a href="{{ url('/') }}" class="text-gray-500 hover:text-blue-600 mr-4 flex items-center" title="Kembali ke Beranda"><i data-lucide="arrow-left" class="w-6 h-6"></i></a>
         <h1 class="text-3xl font-bold text-gray-800">Manajemen <span class="text-blue-600">Kegiatan</span></h1>
    </div>

    <div class="flex justify-between items-center mb-4">
         <h2 class="text-lg font-semibold text-gray-700">Daftar Kegiatan Anda</h2>
         <button @click="openModal('add')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-1 font-medium text-sm transition duration-150 ease-in-out shadow-sm hover:shadow">
            <i data-lucide="plus" class="w-5 h-5"></i><span>Tambah Kegiatan</span>
         </button>
     </div>

    <div x-show="isLoading" class="text-center py-12" x-cloak>
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
        <p class="text-gray-500">Memuat data kegiatan...</p>
    </div>

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
                        <td class="px-4 py-3" x-text="formatDate(event.date)"></td>
                        <td class="px-4 py-3" x-text="event.location"></td>
                        <td class="px-4 py-3">
                            <span x-text="event.volunteers_count"></span> / <span x-text="event.volunteers_needed"></span>
                        </td>
                        <td class="px-4 py-3 flex space-x-1 items-center">
                             <button @click="openDetail(event)" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-100 transition duration-150">
                                 <i data-lucide="info" class="w-4 h-4"></i>
                             </button>
                             <button @click="openModal('edit', event)" class="text-yellow-500 hover:text-yellow-700 p-1 rounded hover:bg-yellow-100 transition duration-150">
                                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                              </button>
                             <button @click="confirmDelete(event)" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-100 transition duration-150">
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

    <div x-show="isFormModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl flex flex-col" style="max-height: 90vh;" @click.away="closeModal()">
            <div class="px-6 py-4 border-b flex justify-between items-center flex-shrink-0 rounded-t-xl">
                <h2 class="text-xl font-bold text-gray-800" x-text="isEditMode ? 'Edit Kegiatan' : 'Tambah Kegiatan'"></h2>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
            </div>
            
            <div class="modal-content-area px-6 pt-6 flex-grow">
                <form @submit.prevent="submitForm">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                        <div>
                            <label class="font-semibold block mb-1 text-sm text-gray-700">Judul Kegiatan *</label>
                            <input type="text" x-model="formData.title" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="font-semibold block mb-1 text-sm text-gray-700">Kategori *</label>
                            <select x-model="formData.category" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required>
                                <option value="">-- Pilih --</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Lingkungan">Lingkungan</option>
                                <option value="Kesehatan">Kesehatan</option>
                            </select>
                        </div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Tanggal *</label><input type="date" x-model="formData.date" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Waktu *</label><input type="text" x-model="formData.time" placeholder="09:00 - 12:00" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Lokasi *</label><input type="text" x-model="formData.location" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Volunteer Dibutuhkan *</label><input type="number" x-model="formData.volunteers_needed" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Kontak Telepon *</label><input type="text" x-model="formData.contact_phone" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        <div><label class="font-semibold block mb-1 text-sm text-gray-700">Email Penyelenggara *</label><input type="email" x-model="formData.contact_email" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></div>
                        
                        <div class="md:col-span-2">
                            <label class="font-semibold block mb-1 text-sm text-gray-700">Foto (Opsional)</label>
                            <input type="file" @change="handleFileChange" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300">
                        </div>
                        <div class="md:col-span-2">
                            <label class="font-semibold block mb-1 text-sm text-gray-700">Deskripsi *</label>
                            <textarea x-model="formData.description" rows="4" class="w-full border rounded-lg p-2 mt-1 text-sm border-gray-300 focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <button type="button" @click="closeModal()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium text-sm">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium text-sm" x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Kegiatan'"></button>
                    </div>
                </form>
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
            
            isFormModalOpen: false,
            isEditMode: false,
            formData: { id: null, title: '', category: '', date: '', time: '', location: '', volunteers_needed: 1, contact_phone: '', contact_email: '', description: '', photo: null },
            
            notification: { show: false, message: '', isError: false },

            initData() {
                if (!this.token) { window.location.href = '/organizer/login'; return; }
                this.fetchEvents();
                setTimeout(() => lucide.createIcons(), 500);
            },

            async fetchEvents() {
                try {
                    const res = await fetch('/api/my-events', {
                        headers: { 'Authorization': `Bearer ${this.token}` }
                    });
                    if (res.status === 401) { alert('Sesi habis.'); window.location.href = '/organizer/login'; return; }
                    const json = await res.json();
                    this.events = json.data;
                } catch (e) {
                    this.showNotify('Gagal memuat data.', true);
                } finally {
                    this.isLoading = false;
                    setTimeout(() => lucide.createIcons(), 100);
                }
            },

            openModal(mode, event = null) {
                this.isEditMode = (mode === 'edit');
                if (this.isEditMode && event) {
                    this.formData = { ...event, photo: null }; // Copy data
                } else {
                    this.formData = { id: null, title: '', category: 'Pendidikan', date: '', time: '', location: '', volunteers_needed: 1, contact_phone: '', contact_email: '', description: '', photo: null };
                }
                this.isFormModalOpen = true;
            },

            closeModal() { this.isFormModalOpen = false; },

            handleFileChange(e) { this.formData.photo = e.target.files[0]; },

            async submitForm() {
                const url = this.isEditMode ? `/api/events/${this.formData.id}` : '/api/events';
                const payload = new FormData();
                
                // Append all fields
                for (const key in this.formData) {
                    if (this.formData[key] !== null) payload.append(key, this.formData[key]);
                }
                if(this.isEditMode) payload.append('_method', 'POST'); // Laravel trick for PUT file upload

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Authorization': `Bearer ${this.token}` },
                        body: payload
                    });
                    
                    if(!res.ok) throw new Error();
                    
                    this.showNotify(this.isEditMode ? 'Berhasil diperbarui' : 'Berhasil dibuat');
                    this.closeModal();
                    this.fetchEvents();
                } catch (e) {
                    this.showNotify('Gagal menyimpan. Cek input.', true);
                }
            },

            async confirmDelete(event) {
                if(!confirm(`Hapus kegiatan "${event.title}"?`)) return;
                try {
                    await fetch(`/api/events/${event.id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${this.token}` }
                    });
                    this.showNotify('Kegiatan dihapus');
                    this.fetchEvents();
                } catch(e) {
                    this.showNotify('Gagal menghapus', true);
                }
            },

            showNotify(msg, isErr = false) {
                this.notification = { show: true, message: msg, isError: isErr };
                setTimeout(() => this.notification.show = false, 3000);
            },

            formatDate(dateStr) {
                if(!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            }
        }
    }
</script>
@endpush
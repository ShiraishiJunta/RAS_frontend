@extends('layouts.app')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-10">
  <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
    
    <div class="mb-8 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Daftar Sebagai Relawan</h2>
        <p class="text-gray-600">
          Anda akan mendaftar untuk kegiatan: <br>
          <span class="font-bold text-blue-600 text-lg">{{ $event->title }}</span>
        </p>
    </div>

    <div id="alert-box" class="hidden mb-6 p-4 rounded-lg text-sm"></div>

    <form id="volunteerForm" onsubmit="handleRegistration(event)">
      <div class="space-y-5">
          
          <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Nama sesuai identitas" required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="contoh@email.com" required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" id="phone" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="08..." required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Alamat Domisili</label>
            <input type="text" name="address" id="address" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Kota/Kabupaten tempat tinggal" required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Alasan Memilih Kegiatan Ini</label>
            <textarea name="reason" id="reason" rows="4" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Ceritakan motivasi Anda..." required></textarea>
          </div>

          <div class="pt-4 flex gap-3">
            <a href="{{ url('/kegiatan') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="submit" id="btn-submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex justify-center items-center">
              <span>Kirim Pendaftaran</span>
            </button>
          </div>
      </div>
    </form>

    <div id="success-view" class="hidden text-center py-10">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check-circle" class="w-10 h-10 text-green-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Terima Kasih!</h3>
        <p class="text-gray-600 mb-8">Pendaftaran Anda berhasil dikirim. Pihak penyelenggara akan segera menghubungi Anda.</p>
        <a href="{{ url('/kegiatan') }}" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
            Cari Kegiatan Lain
        </a>
    </div>

  </div>
</main>

{{-- Script Integrasi API --}}
@push('scripts')
<script>
    async function handleRegistration(e) {
        e.preventDefault(); // Mencegah reload halaman

        // Ambil ID Event dari Blade PHP
        const eventId = {{ $event->id }};
        
        // Ambil Elemen UI
        const btn = document.getElementById('btn-submit');
        const alertBox = document.getElementById('alert-box');
        const formContainer = document.getElementById('volunteerForm');
        const successView = document.getElementById('success-view');

        // Reset State
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
        alertBox.classList.add('hidden');
        alertBox.className = "hidden mb-6 p-4 rounded-lg text-sm"; // Reset class warna

        // Siapkan Data JSON
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            reason: document.getElementById('reason').value,
        };

        try {
            // Panggil API (POST /api/events/{id}/volunteers)
            const response = await fetch(`/api/events/${eventId}/volunteers`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (!response.ok) {
                // Jika Error Validasi (422)
                if (response.status === 422) {
                    let errorMessages = Object.values(result).flat().join('<br>'); // Gabung pesan error
                    throw new Error(errorMessages || 'Data tidak valid.');
                }
                throw new Error(result.message || 'Gagal mendaftar. Silakan coba lagi.');
            }

            // --- JIKA SUKSES ---
            formContainer.classList.add('hidden'); // Sembunyikan form
            successView.classList.remove('hidden'); // Tampilkan pesan sukses
            
            // Scroll ke atas agar terlihat
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } catch (error) {
            // --- JIKA GAGAL ---
            alertBox.innerHTML = `<strong>Oops!</strong> <br> ${error.message}`;
            alertBox.className = "mb-6 p-4 rounded-lg text-sm bg-red-100 text-red-700 border border-red-200"; // Style Error
            alertBox.classList.remove('hidden');
            
            // Kembalikan Tombol
            btn.disabled = false;
            btn.innerHTML = `<span>Kirim Pendaftaran</span>`;
            
            // Scroll ke pesan error
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
</script>
@endpush
@endsection
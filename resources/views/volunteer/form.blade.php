@extends('layouts.app')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-10">
  <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Sebagai Relawan</h2>

    <p class="mb-4 text-gray-600">
      Anda akan mendaftar untuk kegiatan:
      <span class="font-semibold text-blue-600">{{ $event->title }}</span>
    </p>

    <form action="{{ route('volunteer.store', $event->id) }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Alamat</label>
        <input type="text" name="address" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">No. Telepon</label>
        <input type="text" name="phone" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Alasan Memilih Kegiatan Ini</label>
        <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required></textarea>
      </div>

      <div class="pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
          Kirim Pendaftaran
        </button>
      </div>
    </form>
  </div>
</main>
@endsection

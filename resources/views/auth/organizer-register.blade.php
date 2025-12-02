<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Penyelenggara</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Daftar Penyelenggara</h2>

    @if ($errors->any())
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ $errors->first() }}
      </div>
    @endif

        <form action="/organizer/register" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Nama</label>
        <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2" value="{{ old('name') }}">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Email</label>
        <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2" value="{{ old('email') }}">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Password</label>
        <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2">
    </div>

    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
        Daftar
    </button>
</form>

    <p class="text-center text-gray-600 mt-4 text-sm">
      Sudah punya akun? <a href="/organizer/login" class="text-blue-600 hover:underline">Login di sini</a>
    </p>
  </div>
</body>
</html>

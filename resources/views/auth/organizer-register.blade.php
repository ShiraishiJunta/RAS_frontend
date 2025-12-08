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
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Daftar Penyelenggara</h2>

    <div id="error-alert" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm border border-red-200"></div>
    
    <div id="success-alert" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4 text-sm border border-green-200"></div>

    <form id="registerForm" onsubmit="handleRegister(event)">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Nama Organisasi</label>
            <input type="text" id="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" placeholder="Contoh: Yayasan Peduli Kasih">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Email</label>
            <input type="email" id="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" placeholder="email@organisasi.com">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Password</label>
            <input type="password" id="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" placeholder="Minimal 8 karakter">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2 font-medium">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" placeholder="Ulangi password">
        </div>

        <button type="submit" id="btn-submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-bold transition-colors shadow-sm flex justify-center items-center">
            <span>Daftar Sekarang</span>
        </button>
    </form>

    <p class="text-center text-gray-600 mt-6 text-sm">
      Sudah punya akun? <a href="/organizer/login" class="text-blue-600 hover:underline font-medium">Login di sini</a>
    </p>
  </div>

  <script>
    async function handleRegister(e) {
        e.preventDefault(); // Mencegah reload halaman

        // Ambil elemen UI
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const btn = document.getElementById('btn-submit');
        const errorAlert = document.getElementById('error-alert');
        const successAlert = document.getElementById('success-alert');

        // Reset UI
        errorAlert.classList.add('hidden');
        successAlert.classList.add('hidden');
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

        // Validasi Frontend Sederhana
        if (password !== passwordConfirmation) {
            errorAlert.innerText = "Konfirmasi password tidak cocok.";
            errorAlert.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = "Daftar Sekarang";
            return;
        }

        try {
            // Panggil API Register
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password
                })
            });

            const data = await response.json();

            if (!response.ok) {
                // Tangani Error Validasi dari API
                let errorMessage = data.message || 'Registrasi gagal.';
                if (data.errors) {
                    // Ambil error pertama saja jika ada banyak
                    const firstErrorKey = Object.keys(data.errors)[0];
                    errorMessage = data.errors[firstErrorKey][0];
                }
                throw new Error(errorMessage);
            }

            // SUKSES
            // Simpan token agar user langsung login
            localStorage.setItem('auth_token', data.access_token);
            localStorage.setItem('user_data', JSON.stringify(data.data));
            localStorage.setItem('login_success_popup', 'true'); // Trigger popup di dashboard

            successAlert.innerText = 'Registrasi Berhasil! Mengalihkan...';
            successAlert.classList.remove('hidden');

            // Redirect ke Dashboard
            setTimeout(() => {
                window.location.href = '/organizer/events';
            }, 1000);

        } catch (error) {
            errorAlert.innerText = error.message;
            errorAlert.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = "Daftar Sekarang";
        }
    }
  </script>
</body>
</html>
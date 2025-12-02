<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Penyelenggara</title>
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- AlpineJS untuk interaksi sederhana --}}
  <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  
  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md" x-data="loginForm()">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login Penyelenggara</h2>

    {{-- Alert Error --}}
    <div x-show="errorMessage" x-transition class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm flex items-start" x-cloak>
        <span class="mr-2">⚠️</span>
        <span x-text="errorMessage"></span>
    </div>

    {{-- Alert Success --}}
    <div x-show="successMessage" x-transition class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm flex items-start" x-cloak>
        <span class="mr-2">✅</span>
        <span x-text="successMessage"></span>
    </div>

    <form @submit.prevent="submitLogin">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-2 font-medium">Email</label>
            <input type="email" x-model="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 mb-2 font-medium">Password</label>
            <input type="password" x-model="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-bold transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" :disabled="isLoading">
            <span x-show="!isLoading">Masuk</span>
            <span x-show="isLoading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memproses...
            </span>
        </button>
    </form>

    <p class="text-center text-gray-600 mt-6 text-sm">
      Belum punya akun? <a href="/organizer/register" class="text-blue-600 hover:underline font-medium">Daftar di sini</a>
    </p>
  </div>

  <script>
    function loginForm() {
        return {
            email: '',
            password: '',
            isLoading: false,
            errorMessage: '',
            successMessage: '',
            
            async submitLogin() {
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';

                try {
                    // Panggil API Login
                    // Menggunakan URL dari .env (pastikan di view layout atau hardcode sementara)
                    const response = await fetch('/api/login', { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Login gagal. Periksa kembali email dan password.');
                    }

                    // SUKSES: Simpan Token
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(data.data)); // Simpan info user
                    
                    // ==> TAMBAHKAN BARIS INI UNTUK MEMICU POP-UP:
                    localStorage.setItem('login_success_popup', 'true'); 

                    this.successMessage = 'Login berhasil! Mengalihkan...';
                    
                    // Redirect ke Dashboard setelah 1 detik
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1000);

                } catch (error) {
                    this.errorMessage = error.message;
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }
  </script>
</body>
</html>
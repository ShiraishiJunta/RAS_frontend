<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relawan Aksi Sosial - Bersama untuk Kemanusiaan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        [x-cloak] { display: none !important; }
        
        /* Animasi Kustom untuk Judul */
        @keyframes shine {
            to { background-position: 200% center; }
        }
        .text-gradient-animate {
            background: linear-gradient(to right, #2563eb, #9333ea, #2563eb);
            background-size: 200% auto;
            color: #000;
            background-clip: text;
            text-fill-color: transparent;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 5s linear infinite;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                {{-- Logo & Judul (UPGRADED) --}}
                <div class="flex items-center space-x-3 group cursor-pointer">
                     <a href="{{ url('/') }}" class="flex items-center gap-3">
                         <div class="relative w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 ease-out">
                             <i data-lucide="heart-handshake" class="text-white w-6 h-6"></i>
                             <div class="absolute inset-0 bg-blue-500 rounded-xl blur opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>
                         </div>
                         
                         <div class="flex flex-col">
                             <h1 class="text-xl font-extrabold tracking-tight group-hover:text-blue-600 transition-colors duration-300">
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700 group-hover:from-blue-600 group-hover:to-indigo-600 transition-all">
                                    Relawan Aksi Sosial
                                </span>
                             </h1>
                             <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider group-hover:tracking-widest transition-all duration-300">
                                 Humanity & Community
                             </p>
                         </div>
                     </a>
                 </div>

                {{-- Navigasi (Sama seperti sebelumnya) --}}
                <div class="flex items-center space-x-6">
                    <nav class="hidden md:flex space-x-2">
                        <!-- <a href="{{ url('/') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : '' }}">
                           Beranda
                        </a> -->
                        <a href="{{ url('/kegiatan') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 {{ request()->is('kegiatan*') ? 'bg-blue-50 text-blue-600' : '' }}">
                           Kegiatan
                        </a>
                        <a href="{{ url('/organizer/events') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 {{ request()->is('organizer*') ? 'bg-blue-50 text-blue-600' : '' }}">
                           Penyelenggara
                        </a>
                    </nav>

                    {{-- Logic Auth API --}}
                    <div id="auth-section" style="visibility: hidden;">
                        
                        <div id="nav-logged-in" class="hidden flex items-center space-x-3">
                            <span id="nav-user-name" class="text-gray-700 text-sm font-semibold bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                Halo, User
                            </span>
                            <button onclick="handleLogout()"
                                class="flex items-center gap-1 bg-white text-red-500 border border-red-200 hover:bg-red-50 hover:border-red-300 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Logout</span>
                            </button>
                        </div>

                        <div id="nav-guest" class="hidden flex space-x-3">
                            <a href="{{ url('/organizer/login') }}"
                                class="text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50">
                                Login
                            </a>
                            <a href="{{ url('/organizer/register') }}"
                                class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                Daftar
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-white py-12 border-t border-gray-200 mt-20">
         <div class="max-w-7xl mx-auto px-6 text-center">
             <div class="flex justify-center items-center gap-2 mb-4 opacity-50 hover:opacity-100 transition-opacity">
                 <i data-lucide="heart-handshake" class="w-6 h-6 text-blue-600"></i>
                 <span class="font-bold text-gray-800">Relawan Aksi Sosial</span>
             </div>
             <p class="text-gray-500 text-sm mb-6">&copy; {{ date('Y') }} Membangun kebaikan bersama komunitas.</p>
             <div class="flex justify-center space-x-6 text-gray-400">
                 <i data-lucide="facebook" class="w-5 h-5 hover:text-blue-600 hover:scale-110 cursor-pointer transition-all"></i>
                 <i data-lucide="twitter" class="w-5 h-5 hover:text-sky-500 hover:scale-110 cursor-pointer transition-all"></i>
                 <i data-lucide="instagram" class="w-5 h-5 hover:text-pink-500 hover:scale-110 cursor-pointer transition-all"></i>
                 <i data-lucide="mail" class="w-5 h-5 hover:text-red-500 hover:scale-110 cursor-pointer transition-all"></i>
             </div>
         </div>
     </footer>

    {{-- Kode Pop-up & Script tetap sama di bawah ini --}}
    <div x-data="{ show: false }"
         x-init="if(localStorage.getItem('login_success_popup') === 'true') { show = true; localStorage.removeItem('login_success_popup'); }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 backdrop-blur-sm" x-cloak
         @keydown.escape.window="show = false">
         <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center border border-gray-100">
              <div class="mx-auto w-20 h-20 mb-4 bg-green-50 rounded-full flex items-center justify-center">
                  <svg class="w-12 h-12 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
              </div>
             <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
             <p class="text-gray-500 mb-6">Anda berhasil masuk ke dashboard.</p>
             <button @click="show = false"
                     class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-blue-700 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg shadow-blue-200">
                 Mulai Menjelajah
             </button>
         </div>
    </div>

    @include('partials.popup-error')

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const token = localStorage.getItem('auth_token');
            const authSection = document.getElementById('auth-section');
            const navGuest = document.getElementById('nav-guest');
            const navLoggedIn = document.getElementById('nav-logged-in');
            const userNameDisplay = document.getElementById('nav-user-name');

            if (token) {
                const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
                if(userData.name) userNameDisplay.innerText = 'Halo, ' + userData.name;
                navLoggedIn.classList.remove('hidden');
                navGuest.classList.add('hidden');
            } else {
                navGuest.classList.remove('hidden');
                navLoggedIn.classList.add('hidden');
            }
            authSection.style.visibility = 'visible';
        });

        async function handleLogout() {
            if(!confirm('Anda yakin ingin keluar?')) return;
            const token = localStorage.getItem('auth_token');
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
            } catch (error) { console.log('Logout error', error); }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            window.location.href = '/organizer/login';
        }
    </script>
</body>
</html>
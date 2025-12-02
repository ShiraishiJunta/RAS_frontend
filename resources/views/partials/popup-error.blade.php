{{-- 
  File ini akan kita 'include' di landing.blade.php dan kegiatan.blade.php.
  Ia akan otomatis muncul jika ada session 'error_popup'
--}}
@if (session('error_popup'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    class="fixed bottom-5 right-5 z-50"
    style="display: none;"
>
    <div class="bg-red-600 text-white font-semibold rounded-lg shadow-lg p-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 mr-3">
            <path d="M18 6 6 18"></path><path d="m6 6 12 12"></path>
        </svg>
        <span>{{ session('error_popup') }}</span>
        <button @click="show = false" class="ml-4 text-red-100 hover:text-white">
            &times;
        </button>
    </div>
</div>
@endif

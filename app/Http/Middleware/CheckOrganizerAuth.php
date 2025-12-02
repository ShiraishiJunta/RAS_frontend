<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah session 'organizer' TIDAK ada
        if (!session()->has('organizer')) {
            
            // --- INI PERUBAHANNYA ---
            // Kembalikan pengguna ke halaman SEBELUMNYA (misalnya /kegiatan)
            // sambil mengirimkan pesan error untuk ditampilkan sebagai pop-up.
            return redirect()->back()
                             ->with('error_popup', 'Anda harus login sebagai penyelenggara untuk mengakses halaman tersebut.');
            // -------------------------
        }

        // Jika session 'organizer' ada, lanjutkan ke request berikutnya (ke controller)
        return $next($request);
    }
}


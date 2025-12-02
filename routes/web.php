<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerAuthController;
use App\Http\Controllers\VolunteerController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Untuk Pengunjung)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/kegiatan', function () {
    return view('kegiatan');
})->name('kegiatan.index');


/*
|--------------------------------------------------------------------------
| Rute Penyelenggara (Organizer)
|--------------------------------------------------------------------------
*/

// Halaman Login & Register (Hanya Tampilan)
Route::prefix('organizer')->group(function () {
    Route::get('/login', [OrganizerAuthController::class, 'showLogin'])->name('organizer.login.show');
    Route::get('/register', [OrganizerAuthController::class, 'showRegister'])->name('organizer.register');
    Route::get('organizer/logout', [OrganizerAuthController::class, 'logout'])->name('organizer.logout');
    // Hapus Route::post login/register versi web karena sudah pakai API
});

// Halaman Dashboard Events (Sekarang Tanpa Middleware Session!)
// Kita biarkan halaman ini dimuat, nanti JS yang cek token.
Route::prefix('organizer')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('organizer.events');
});



Route::get('/volunteer/register/{event}', [VolunteerController::class, 'create'])->name('volunteer.create');
Route::post('/volunteer/register/{event}', [VolunteerController::class, 'store'])->name('volunteer.store');



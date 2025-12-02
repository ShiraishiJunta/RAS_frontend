<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Validation\Rule; // Import Rule

class VolunteerController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        // Tambahkan cek apakah acara masih open/belum penuh jika perlu
        return view('volunteer.form', compact('event'));
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId); // Ambil data event

        $request->validate([
            // --- VALIDASI UNIK ---
            'email' => [
                'required',
                'email',
                // Pastikan email ini unik HANYA untuk event_id ini di tabel volunteers
                Rule::unique('volunteers')->where(function ($query) use ($eventId) {
                    return $query->where('event_id', $eventId);
                }),
            ],
            // --- AKHIR VALIDASI UNIK ---
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'reason' => 'required|string|max:500',
        ], [
            // Pesan error custom jika email sudah terdaftar di event ini
            'email.unique' => 'Email ini sudah terdaftar sebagai relawan untuk kegiatan ini.',
        ]);

        // Opsional: Cek apakah jumlah pendaftar sudah melebihi kuota
        $currentVolunteers = Volunteer::where('event_id', $eventId)->count();
        if ($currentVolunteers >= $event->volunteers_needed) {
            return back()->with('error', 'Maaf, kuota relawan untuk kegiatan ini sudah penuh.');
        }


        Volunteer::create([
            'event_id' => $eventId,
            'email' => $request->email,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'reason' => $request->reason,
        ]);

        // Redirect ke halaman kegiatan agar bisa lihat perubahan jumlah
        return redirect()->route('kegiatan.index')->with('success', 'Pendaftaran Anda sebagai relawan berhasil!');
    }
}

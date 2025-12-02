<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    // --- TAMBAHAN BARU: Method Index ---
    // Hanya bisa diakses oleh Organizer pemilik event
    public function index(Request $request, $eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        // Cek Authorization: Apakah user yang login adalah pemilik event ini?
        if ($event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Anda tidak berhak melihat data relawan event ini'], 403);
        }

        // Ambil data relawan
        $volunteers = $event->volunteers()->latest()->get();

        return response()->json([
            'message' => 'List Relawan Event: ' . $event->title,
            'data' => $volunteers
        ]);
    }
    // --- AKHIR TAMBAHAN ---
    // Method untuk mendaftar sebagai relawan di event tertentu
    public function store(Request $request, $eventId)
    {
        // 1. Cek apakah Event ada
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        // 2. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'reason' => 'required|string|max:500',
            // Validasi Email Unik per Event (Satu email tidak bisa daftar double di event yang sama)
            'email' => [
                'required',
                'email',
                Rule::unique('volunteers')->where(function ($query) use ($eventId) {
                    return $query->where('event_id', $eventId);
                }),
            ],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai relawan untuk kegiatan ini.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 3. Cek Kuota (Opsional tapi disarankan)
        // Hitung jumlah relawan yang sudah daftar
        $currentVolunteers = Volunteer::where('event_id', $eventId)->count();
        
        // Jika kuota penuh
        if ($currentVolunteers >= $event->volunteers_needed) {
            return response()->json(['message' => 'Maaf, kuota relawan untuk kegiatan ini sudah penuh.'], 400);
        }

        // 4. Simpan Data
        $volunteer = Volunteer::create([
            'event_id' => $eventId,
            'email' => $request->email,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'message' => 'Pendaftaran Relawan Berhasil!',
            'data' => $volunteer
        ], 201);
    }
}
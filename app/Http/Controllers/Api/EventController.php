<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // 1. Tampilkan Semua Event (Public)
    public function index()
    {
        $events = Event::with('organizer')
                       ->withCount('volunteers')
                       ->latest()
                       ->get();

        return response()->json(['message' => 'List Semua Event', 'data' => $events]);
    }

    // 2. Buat Event Baru (Perlu Login)
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'description' => 'required|string',
            'volunteers_needed' => 'required|integer|min:1',
            // TAMBAHAN PENTING: Validasi untuk kontak
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle Upload Foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('events', 'public'); 
        }

        // Simpan ke Database
        $event = Event::create([
            'organizer_id' => $request->user()->id, 
            'title' => $request->title,
            'category' => $request->category,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'description' => $request->description,
            'volunteers_needed' => $request->volunteers_needed,
            // TAMBAHAN PENTING: Masukkan data kontak ke database
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'message' => 'Event Berhasil Dibuat',
            'data' => $event
        ], 201);
    }

    // 3. Update Event (Perlu Login & Pemilik Event)
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        if ($event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Anda tidak berhak mengedit event ini'], 403);
        }

        // Validasi parsial (tambahkan validation untuk kontak jika perlu diupdate)
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'contact_email' => 'sometimes|email', // Opsional
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }
            $data['photo'] = $request->file('photo')->store('events', 'public');
        }

        $event->update($data);

        return response()->json([
            'message' => 'Event Berhasil Diupdate',
            'data' => $event
        ]);
    }

    // ... (Method destroy dan myEvents tetap sama)
    public function destroy(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) return response()->json(['message' => 'Event tidak ditemukan'], 404);
        if ($event->organizer_id !== $request->user()->id) return response()->json(['message' => 'Forbidden'], 403);
        if ($event->photo) Storage::disk('public')->delete($event->photo);
        $event->delete();
        return response()->json(['message' => 'Event Berhasil Dihapus']);
    }

    public function myEvents(Request $request)
    {
        $events = Event::where('organizer_id', $request->user()->id)
                       ->withCount('volunteers')
                       ->latest()
                       ->get();
        return response()->json(['message' => 'Event Saya', 'data' => $events]);
    }
}
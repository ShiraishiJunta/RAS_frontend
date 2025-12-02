<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Event; // <-- Baris ini boleh dihapus/dikomentari karena tidak dipakai lagi

class EventController extends Controller
{
    public function index()
    {
        // VERSI LAMA (HAPUS/KOMENTARI):
        // $events = Event::with('organizer')->latest()->get();
        // return view('kegiatan', compact('events'));

        // VERSI BARU (V2 API):
        // Cukup tampilkan kulit luarnya saja. Biar Javascript yang isi datanya.
        return view('organizers.events');
    }
}
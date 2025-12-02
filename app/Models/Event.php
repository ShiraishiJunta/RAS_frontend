<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'date',
        'time',
        'location',
        'contact_phone',
        'contact_email',
        'organizer_id',
        'description',
        'photo',
        'volunteers_needed',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    // --- UBAH RELASI INI ---
    public function volunteers() // Ganti nama dari registrations menjadi volunteers
    {
        return $this->hasMany(Volunteer::class); // Hubungkan ke model Volunteer
    }
    // --- AKHIR PERUBAHAN ---
}

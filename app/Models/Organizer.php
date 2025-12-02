<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Ubah dari Model ke Authenticatable
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import Trait Sanctum

class Organizer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // Tambahkan field lain jika ada di database, misal: 'phone', 'website'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function events()
    {
        // Satu organizer bisa memiliki banyak event
        return $this->hasMany(Event::class);
    }
}
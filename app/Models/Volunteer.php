<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'email',
        'name',
        'address',
        'phone',
        'reason',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

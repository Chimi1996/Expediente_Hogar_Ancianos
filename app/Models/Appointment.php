<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'doctor_name',
        'specialty',
        'appointment_datetime',
        'location',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}

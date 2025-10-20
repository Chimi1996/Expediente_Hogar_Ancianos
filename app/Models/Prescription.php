<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'medication_id',
        'dose',
        'frequency',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}

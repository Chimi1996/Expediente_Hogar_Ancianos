<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;
    protected $fillable = [
        'resident_id',
        'description',
        'diagnosis_date',
        'status',
        'notes',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}

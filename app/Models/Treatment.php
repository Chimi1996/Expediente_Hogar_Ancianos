<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'diagnosis_id',
        'description',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}

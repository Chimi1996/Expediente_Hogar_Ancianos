<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'presentation',
        'standard_dose',
        'description',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}

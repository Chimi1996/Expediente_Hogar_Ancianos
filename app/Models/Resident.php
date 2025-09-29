<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identity_card', //cedula
        'first_name', //primer_nombre
        'second_name', //segundo_nombre
        'last_name', //primer_apellido
        'second_last_name', //segundo_apellido
        'sex', //sexo 'Masculino' o 'Femenino'
        'birth_date', //fecha_nacimiento
        'admission_date', //fecha_ingreso
        'status', //estado 'Activo', 'Inactivo', 'Trasladado', 'Fallecido'
    ];
        

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comida extends Model
{
    use HasFactory;

    //Relations
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    protected $fillable = [
        'empleado_id',
        'tipo',
    ];
}

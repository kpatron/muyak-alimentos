<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee_name',
        'employee_signature',
        'qr_code',
    ];

    //Relations
    public function comidas()
    {
        return $this->hasMany(Comida::class);
    }
}

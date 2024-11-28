<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bibliotecas extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'bibliotecas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Id',
        'Nombre',
        'Fecha_Inventario',
        'Tabla',
    ];
}

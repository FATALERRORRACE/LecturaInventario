<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{

    /**
     * table name
     *
     * @var string
     */
    protected $table = 'master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Id',
        'C_Barras',
        'Titulo',
        'Autor',
        'Clasificacion',
        'Isbn',
        'Descripcion',
        'Precio',
        'Estadistica',
        'Biblioteca',
        'Material',
        'Localizacion',
        'Proceso',
        'Creacion',
        'Acervo'
    ];

}

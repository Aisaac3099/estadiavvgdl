<?php namespace App\Models;

use CodeIgniter\Model;

class TecnicoModel extends Model
{
    protected $table = 'tecnicos'; // Nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre'
    ];

  
}

 
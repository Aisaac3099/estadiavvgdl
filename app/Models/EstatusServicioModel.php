<?php namespace App\Models;

use CodeIgniter\Model;

class EstatusServicioModel extends Model
{
    protected $table = 'estatusservicio'; // Nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'estatus'
    ];

  
}

 
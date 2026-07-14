<?php namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table = 'servicios'; // Nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'cliente', 
        'servicio', 
        'estatusServ', 
        'cotizacion',
        'material',
        'estatusMat', 
        'estimacionTiempo',
        'registrante',
        'fechaRegistrante', 
    ];

  
}

 
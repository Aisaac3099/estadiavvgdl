<?php namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'cliente'; // Nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre', 
        'direccion', 
        'telefono',
        'correo',
        'comentarios'
    ];

  
}

 
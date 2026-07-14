<?php namespace App\Models;

use CodeIgniter\Model;

class EstatusPagoModel extends Model
{
    protected $table = 'estatuspago'; // Nombre de la tabla
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'estatus'
    ]; 
}

 
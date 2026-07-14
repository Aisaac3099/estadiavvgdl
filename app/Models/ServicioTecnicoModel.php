<?php namespace App\Models;

use CodeIgniter\Model;

class ServicioTecnicoModel extends Model
{
    protected $table = 'servicio_tecnicos'; // Nombre de la tabla 
    protected $allowedFields = ['agenda_id', 'tecnico_id'];
  
}

 
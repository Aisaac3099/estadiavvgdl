<?php namespace App\Models;

use CodeIgniter\Model;

class AgendaModel extends Model
{
    protected $table = 'agenda'; // Nombre de la tabla
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;


    protected $allowedFields = [
        'cliente', 
        'servicio',
        'tecnico',  
        'fechaInicio', 
        'comentarios',  
        'ordenServicio',  
        'estatusPago',
        'registrante',
        'prioridad',
    
    ];
    //30/09/2025 
    // Método para obtener todos los eventos ordenados por fechaInicio
    public function getEventosOrdenados()
    {
        return $this->orderBy('fechaInicio', 'ASC')->findAll();
    }
  
}

 
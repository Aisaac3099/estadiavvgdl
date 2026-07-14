<?php namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'titulo',
        'mensaje',
        'modulo',
        'referencia_id',
        'tipo',
        'leida'
    ];
    protected $useTimestamps = true;
    
    protected $returnType = 'array';
}
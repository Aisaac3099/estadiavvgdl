<?php namespace App\Models;

use CodeIgniter\Model;

class NotificacionLeidaModel extends Model
{
    protected $table = 'notificaciones_leidas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'notificacion_id',
        'usuario_id',
        'leida_at',
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';
}

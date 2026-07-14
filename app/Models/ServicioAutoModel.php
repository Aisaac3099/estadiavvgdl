<?php namespace App\Models;

use CodeIgniter\Model;

class ServicioAutoModel extends Model
{
    protected $table = 'servicios_autos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'auto_id',
        'fecha_servicio',
        'tipo_servicio',
        'tipo_registro',
        'estado_servicio',
        'observacion_estado',
        'categoria_servicio',
        'descripcion',
        'proximo_servicio'
    ];
    protected $useTimestamps = true;
}
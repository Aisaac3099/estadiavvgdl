<?php namespace App\Models;

use CodeIgniter\Model;

class ServicioAutoEvidenciaModel extends Model
{
    protected $table = 'servicios_autos_evidencias';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'servicio_auto_id',
        'archivo'
    ];
    protected $useTimestamps = true;
}
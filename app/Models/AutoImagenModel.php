<?php namespace App\Models;

use CodeIgniter\Model;

class AutoImagenModel extends Model
{
    protected $table = 'autos_imagenes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'auto_id',
        'imagen'
    ];
    protected $useTimestamps = true;
}
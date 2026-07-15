<?php namespace App\Models;

use CodeIgniter\Model;

class InventarioModel extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'alias',
        'marca',
        'modelo',
        'cantidad',
        'bodega',
        'anaquel',
        'nivel',
        'activo',
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';
}

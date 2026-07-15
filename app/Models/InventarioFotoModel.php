<?php namespace App\Models;

use CodeIgniter\Model;

class InventarioFotoModel extends Model
{
    protected $table = 'inventario_fotos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'inventario_id',
        'foto',
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';
}

<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email', 
        'password', 
        'permiso', 
        'nombre', 
        'estatus', //Agregue columna estatus
        'nombreUsuario' //02/10/2025 agregue columna 
    ]; 
    protected $useTimestamps = false; // cambie a false, estaba true
}
<?php
namespace App\Controllers;

class TestJSON extends \CodeIgniter\Controller
{
    public function index()
    {
        $path = getenv('FCM_SERVICE_ACCOUNT_PATH');
        echo "Ruta usada: $path <br>";

        if(file_exists($path)) {
            echo "¡Archivo JSON encontrado y accesible!";
        } else {
            echo "ERROR: PHP no puede encontrar el archivo JSON";
        }
    }
}

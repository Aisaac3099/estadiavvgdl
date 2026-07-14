<?php namespace App\Controllers;

use CodeIgniter\Controller;

class CreateCacheDir extends Controller
{
    public function index()
    {
        $cacheDir = WRITEPATH . 'database/';
        
        if (!is_dir($cacheDir)) {
            if (mkdir($cacheDir, 0755, true)) {
                echo "✅ Directorio de cache creado: " . $cacheDir;
            } else {
                echo "❌ Error creando directorio";
            }
        } else {
            echo "✅ El directorio de cache ya existe";
        }
        
        // Verificar permisos
        if (is_writable($cacheDir)) {
            echo "<br>✅ El directorio tiene permisos de escritura";
        } else {
            echo "<br>❌ El directorio NO tiene permisos de escritura";
        }
    }
}
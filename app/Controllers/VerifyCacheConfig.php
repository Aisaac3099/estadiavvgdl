<?php namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Cache;

class VerifyCacheConfig extends Controller
{
    public function index()
    {
        echo "<h2>✅ Verificando Configuración de Cache</h2>";
        
        // Verificar configuración de Cache
        $cacheConfig = new Cache();
        
        echo "<h3>Configuración Cache.php:</h3>";
        echo "Handler: " . $cacheConfig->handler . "<br>";
        echo "Prefix: " . $cacheConfig->prefix . "<br>";
        echo "TTL: " . $cacheConfig->ttl . " segundos<br>";
        echo "Store Path: " . $cacheConfig->file['storePath'] . "<br><br>";
        
        // Verificar configuración de Database
        echo "<h3>Configuración Database:</h3>";
        echo "DBDebug: " . (env('database.default.DBDebug') ? 'true' : 'false') . "<br>";
        
        // Probar el cache
        echo "<h3>Probando Cache:</h3>";
        $cache = \Config\Services::cache();
        
        // Test de escritura
        $testData = ['timestamp' => date('Y-m-d H:i:s'), 'servicios' => 112];
        if ($cache->save('config_test', $testData, 300)) {
            echo "✅ Cache guardado correctamente<br>";
        } else {
            echo "❌ Error guardando cache<br>";
        }
        
        // Test de lectura
        if ($cachedData = $cache->get('config_test')) {
            echo "✅ Cache leído correctamente<br>";
            echo "Datos: " . json_encode($cachedData) . "<br>";
        } else {
            echo "❌ Error leyendo cache<br>";
        }
        
        echo "<h3>🎯 Configuración lista para usar!</h3>";
    }
}
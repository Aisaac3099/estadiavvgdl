<?php namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class CheckDBConfig extends Controller
{
    public function index()
    {
        echo "<h2>🔍 Configuración Actual de Base de Datos</h2>";
        
        try {
            // Obtener configuración directamente del archivo
            $config = config('Database');
            $dbConfig = $config->default;
            
            echo "<h3>Configuración desde Database.php:</h3>";
            echo "<pre>";
            print_r($dbConfig);
            echo "</pre>";
            
            // Probar conexión
            $db = Database::connect();
            echo "<h3>✅ Conexión a BD exitosa</h3>";
            
            // Hacer una consulta de prueba
            echo "<h3>Probando consulta:</h3>";
            $start = microtime(true);
            $result = $db->query('SELECT COUNT(*) as total FROM servicios')->getRow();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo "Tiempo: {$time} ms<br>";
            echo "Total servicios: " . ($result->total ?? 'Error') . "<br>";
            
            // Verificar propiedades del objeto Database
            echo "<h3>Información del objeto Database:</h3>";
            echo "DBDriver: " . ($dbConfig['DBDriver'] ?? 'No definido') . "<br>";
            echo "DBDebug: " . ($dbConfig['DBDebug'] ? 'TRUE' : 'FALSE') . "<br>";
            echo "pConnect: " . ($dbConfig['pConnect'] ? 'TRUE' : 'FALSE') . "<br>";
            
        } catch (\Exception $e) {
            echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
        }
        
        // Verificar archivo .env
        echo "<h3>Variables de entorno relacionadas con BD:</h3>";
        $envVars = [
            'database.default.hostname',
            'database.default.database', 
            'database.default.username',
            'database.default.DBDebug',
            'database.default.pConnect'
        ];
        
        foreach ($envVars as $envVar) {
            $value = env($envVar);
            echo "$envVar: " . ($value ?? 'No definida') . "<br>";
        }
    }
}
<?php 
namespace App\Controllers;

use App\Libraries\FCMService;

class TestFCMToken extends \CodeIgniter\Controller
{
    public function index()
    {
        $fcm = new FCMService();
        $token = $this->obtenerAccessToken($fcm);

        if ($token) {
            echo "Access token FCM generado correctamente:<br>";
            echo $token;
        } else {
            echo "Error al generar el access token. Revisa writable/logs/";
        }
    }

    private function obtenerAccessToken($fcmService)
    {
        // Usamos Reflection para llamar al método privado getAccessToken()
        $reflection = new \ReflectionClass($fcmService);
        $method = $reflection->getMethod('getAccessToken');
        $method->setAccessible(true);
        return $method->invoke($fcmService);
    }
}

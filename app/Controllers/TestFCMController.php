<?php
namespace App\Controllers;

class TestFCMController extends BaseController
{
    public function index()
    {
        echo "<h3>Test FCM con cURL Directo</h3>";
        
        $ruta = '/home1/videovig/sistema.videovigilanciagdl.com.mx/app/Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json';
        
        // Leer el archivo de servicio
        $serviceAccount = json_decode(file_get_contents($ruta), true);
        
        echo "Client Email: " . $serviceAccount['client_email'] . "<br>";
        echo "Project ID: " . $serviceAccount['project_id'] . "<br>";
        
        // Crear el JWT manualmente
        $jwt = $this->createJWT($serviceAccount);
        echo "JWT creado: " . substr($jwt, 0, 50) . "...<br>";
        
        // Obtener access token
        $accessToken = $this->getAccessTokenWithJWT($jwt);
        
        if ($accessToken) {
            echo "<h4 style='color:green'>✅ ÉXITO - Token obtenido!</h4>";
            echo "Token: " . substr($accessToken, 0, 50) . "...<br>";
            
            // Probar a enviar notificación
            $this->testNotificacion($accessToken, $serviceAccount['project_id']);
        } else {
            echo "<h4 style='color:red'>❌ ERROR obteniendo token</h4>";
        }
    }
    
    private function createJWT($serviceAccount)
    {
        $header = json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT'
        ]);
        
        $now = time();
        $payload = json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $dataToSign = $base64Header . "." . $base64Payload;
        
        // Firmar con la clave privada
        $signature = '';
        openssl_sign($dataToSign, $signature, $serviceAccount['private_key'], OPENSSL_ALGO_SHA256);
        
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $dataToSign . "." . $base64Signature;
    }
    
    private function getAccessTokenWithJWT($jwt)
    {
        try {
            $client = \Config\Services::curlrequest();
            
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt
                ],
                'timeout' => 10
            ]);
            
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                return $data['access_token'] ?? false;
            } else {
                echo "Error HTTP: " . $response->getStatusCode() . " - " . $response->getBody() . "<br>";
                return false;
            }
            
        } catch (\Exception $e) {
            echo "Exception: " . $e->getMessage() . "<br>";
            return false;
        }
    }
    
    private function testNotificacion($accessToken, $projectId)
    {
        echo "<h4>Probando notificación...</h4>";
        
        // Necesitarías un token FCM real aquí para probar
        echo "Access Token: " . substr($accessToken, 0, 50) . "...<br>";
        echo "Project ID: " . $projectId . "<br>";
        echo "✅ Listo para enviar notificaciones!<br>";
    }
}
<?php
namespace App\Libraries;

use Exception;

class FCMService
{
    private $projectId;
    private $serviceAccountPath;
    private $clientEmail;
    private $privateKey;

    public function __construct()
    {
        $this->projectId = getenv('FCM_PROJECT_ID');
        $this->serviceAccountPath = '/home1/videovig/sistema.videovigilanciagdl.com.mx/app/Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json';
        
        log_message('debug', 'Ruta JSON FCM: ' . $this->serviceAccountPath);

        // Cargar los datos del service account
        if (!file_exists($this->serviceAccountPath)) {
            log_message('error', 'El archivo JSON de FCM NO existe en: ' . $this->serviceAccountPath);
            return;
        }

        $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
        $this->clientEmail = $serviceAccount['client_email'];
        $this->privateKey = $serviceAccount['private_key'];
        
        log_message('debug', '✅ Service Account cargado correctamente');
    }

    public function getAccessToken()
    {
        try {
            // Crear JWT
            $jwt = $this->createJWT();
            
            // Obtener access token
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
                log_message('debug', '✅ Access token obtenido exitosamente');
                return $data['access_token'];
            } else {
                log_message('error', 'Error obteniendo token: ' . $response->getStatusCode() . ' - ' . $response->getBody());
                return false;
            }
            
        } catch (Exception $e) {
            log_message('error', 'FCM Auth Error: ' . $e->getMessage());
            return false;
        }
    }

    private function createJWT()
    {
        $header = json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT'
        ]);
        
        $now = time();
        $payload = json_encode([
            'iss' => $this->clientEmail,
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
        openssl_sign($dataToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $dataToSign . "." . $base64Signature;
    }

  
    public function enviarNotificacion(string $titulo, string $cuerpo, array $datos)
{
    $accessToken = $this->getAccessToken();
    if (!$accessToken) {
        log_message('error', 'No se pudo obtener access token para FCM');
        return false;
    }

    $tokenModel = model('TokenModel');
    $tokens = $tokenModel->obtenerTokensActivos();
    
    if (empty($tokens)) {
        log_message('error', 'No hay tokens activos para enviar notificación');
        return false;
    }

    $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';
    $success = 0;

    foreach ($tokens as $row) {
        $payload = [
            'message' => [
                'token' => $row['fcm_token'],
                'notification' => [
                    'title' => $titulo, 
                    'body' => $cuerpo
                ],
                'data' => $datos
            ]
        ];
        
        // ✅ AGREGAR ESTO PARA DEBUG
        log_message('debug', 'Payload FCM: ' . json_encode($payload));
        log_message('debug', 'URL: ' . $url);
        log_message('debug', 'Token length: ' . strlen($accessToken));
        
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($payload),
                'timeout' => 10
            ]);

            // ✅ AGREGAR ESTO TAMBIÉN
            log_message('debug', 'Response Status: ' . $response->getStatusCode());
            log_message('debug', 'Response Body: ' . $response->getBody());

            if ($response->getStatusCode() == 200) {
                $success++;
                log_message('debug', 'Notificación enviada exitosamente');
            } else {
                log_message('error', 'Error FCM respuesta: ' . $response->getStatusCode() . ' - ' . $response->getBody());
            }
            
        } catch (Exception $e) {
            log_message('error', 'Error enviando notificación FCM: ' . $e->getMessage());
        }
    }

    log_message('debug', "Resultado envío: {$success}/" . count($tokens) . " exitosos");
    return ['enviados' => $success, 'total' => count($tokens)];
}
}
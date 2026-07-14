<?php namespace App\Libraries;

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
        // Usar la ruta absoluta es correcto, pero asegúrate de que esté accesible:
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

    // ... (getAccessToken y createJWT permanecen iguales) ...

    public function getAccessToken()
    {
        // Tu lógica de autenticación JWT se mantiene aquí
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

        // ... (Tu lógica de truncado de datos se mantiene) ...

        $datosTruncados = [];
        foreach ($datos as $key => $value) {
            if (is_string($value)) {
                $datosTruncados[$key] = substr($value, 0, 100);
            } elseif (is_array($value) || is_object($value)) {
                $jsonValue = json_encode($value);
                $datosTruncados[$key] = substr($jsonValue, 0, 100);
            } else {
                $datosTruncados[$key] = substr(strval($value), 0, 100);
            }
        }
        
        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';
        $success = 0;

        foreach ($tokens as $row) {
            $fcmToken = $row['fcm_token']; // Usamos el token actual en la iteración

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $titulo,
                        'body' => $cuerpo
                    ],
                    'data' => $datosTruncados,
                    'android' => [
                        'priority' => 'high'
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10'
                        ]
                    ]
                ]
            ];
            
            log_message('debug', 'Payload FCM para token ' . substr($fcmToken, 0, 10) . '...: ' . json_encode($payload));
            
            try {
                $client = \Config\Services::curlrequest();
                $response = $client->post($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode($payload),
                    'timeout' => 10,
                    'http_errors' => false // CRUCIAL para manejar errores 4xx/5xx sin excepciones
                ]);

                $statusCode = $response->getStatusCode();
                $responseBody = $response->getBody();
                
                log_message('debug', 'Response Status: ' . $statusCode);
                log_message('debug', 'Response Body: ' . $responseBody);
                
                if ($statusCode == 200) {
                    $success++;
                    log_message('debug', 'Notificación enviada exitosamente al token ' . substr($fcmToken, 0, 10) . '...');
                } else {
                    // INTRODUCIMOS LA LÓGICA DE LIMPIEZA
                    $responseData = json_decode($responseBody, true);
                    $errorCode = $responseData['error']['details'][0]['errorCode'] ?? null;
                    
                    if ($errorCode === 'UNREGISTERED') {
                        // SOLUCIÓN: Si Firebase dice que el token no está registrado, lo eliminamos de la BD.
                        $tokenModel->desactivarPorToken($fcmToken);
                        log_message('error', "🚨 TOKEN LIMPIADO: Error UNREGISTERED detectado. Token {$fcmToken} eliminado/desactivado.");
                        
                    } else {
                        // Otros errores (Auth, Payload, etc.)
                        log_message('error', 'Error FCM NO UNREGISTERED: ' . $statusCode . ' - ' . $responseBody);
                    }
                }
                
            } catch (Exception $e) {
                log_message('error', 'Exception FCM (En el envío): ' . $e->getMessage());
            }
        }

        log_message('debug', "Resultado envío: {$success}/" . count($tokens) . " exitosos");
        return ['enviados' => $success, 'total' => count($tokens)];
    }
}

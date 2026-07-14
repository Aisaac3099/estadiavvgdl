<?php namespace App\Libraries;

// AÑADIDO: Importar la clase de credenciales de Google
use Google\Auth\Credentials\ServiceAccountCredentials;
use Exception; 

class FCMService
{
    private $projectId;
    private $serviceAccountPath;
    
    public function __construct()
    {
        $this->projectId = getenv('FCM_PROJECT_ID');
        // Usamos ROOTPATH y añadimos un DIRECTORY_SEPARATOR (que es '/') para asegurar la ruta.
        #$this->serviceAccountPath = ROOTPATH . getenv('FCM_SERVICE_ACCOUNT_PATH');
        //$filePath= $this->serviceAccountPath = APPPATH . getenv('FCM_SERVICE_ACCOUNT_PATH');
        //$this->serviceAccountPath = APPPATH . getenv('FCM_SERVICE_ACCOUNT_PATH');
        $this->serviceAccountPath = getenv('FCM_SERVICE_ACCOUNT_PATH'); // ya es absoluta
        log_message('debug', 'Ruta FCM usada: ' . $this->serviceAccountPath);
        //echo "Attempting to open file at: " . $filePath;
        //dd($filePath);
    }

    /**
     * Obtiene el token de acceso JWT necesario para autenticarse en la API de FCM.
     * Requiere que la librería google/auth esté instalada.
     * @return string|false El token de acceso o false si falla.
     */
    private function getAccessToken()
    {
        try { 
            // 1. Instancia ServiceAccountCredentials para cargar el JSON
            $credentials = new ServiceAccountCredentials(
                // Ámbito de la API de Firebase Messaging
                ['https://www.googleapis.com/auth/firebase.messaging'], 
                // Carga el contenido del archivo JSON
                file_get_contents($this->serviceAccountPath) 
            );

            // 2. Obtener el token 
            $token = $credentials->fetchAuthToken();

            return $token['access_token'];

        } catch (Exception $e) {
            // Captura cualquier excepción y loguea el error, incluyendo la ruta de fallo.
            log_message('error', 'FCM Auth Error: ' . $e->getMessage() . ' Path: ' . $this->serviceAccountPath);
            return false;
        }
    }

    /**
     * Envía una notificación push a todos los tokens activos.
     * @param string $titulo Título de la notificación.
     * @param string $cuerpo Cuerpo de la notificación.
     * @param array $datosServicio Datos personalizados a enviar.
     * @return array Resultado del envío.
     */
    public function enviarNotificacion(string $titulo, string $cuerpo, array $datosServicio)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            // Fallo en la autenticación.
            return false;
        }

        $tokenModel = model('TokenModel');
        $tokens = $tokenModel->obtenerTokensActivos();
        
        if (empty($tokens)) { 
            log_message('warning', 'FCM Notification Skip: No hay tokens activos en la base de datos.');
            return false; 
        }

        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';
        
        $successCount = 0;

        foreach ($tokens as $row) {
            $token = $row['fcm_token'];
            
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => ['title' => $titulo, 'body' => $cuerpo],
                    'data' => $datosServicio 
                ]
            ];

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ];

            // Usamos la clase nativa de CI4 para HTTP
            $client = \Config\Services::curlrequest();

            $response = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($payload)
            ]);
            
            if ($response->getStatusCode() == 200) {
                $successCount++;
            } else {
                log_message('warning', 'FCM Error for token ' . $token . ': ' . $response->getBody());
                // Podrías implementar lógica para eliminar tokens expirados aquí.
            }
        }
        
        return ['enviados' => $successCount, 'total' => count($tokens)];
    }
}

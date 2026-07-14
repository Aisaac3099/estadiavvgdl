<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TokenModel;

// Nota: Asumo que su controlador extiende BaseController o Controller
class FcmController extends Controller 
{
    public function registrar()
    {
        // 1. OBTENER DATOS JSON
        // Usamos getJSON(true) para leer el cuerpo de la petición JSON
        // y convertirlo en un array asociativo.
        $requestData = $this->request->getJSON(true); 

        // 2. EXTRAER Y VALIDAR VARIABLES (de forma segura)
        $token = $requestData['fcm_token'] ?? null;
        $userId = $requestData['user_id'] ?? null;

        // Validar el token
        if (!$token) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Token FCM requerido'
            ]);
        }
        
        // Validar el ID de usuario (necesario para la función guardarToken)
        if (!$userId) {
             return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID de usuario requerido'
            ]);
        }
        
        // 3. REGISTRAR EL TOKEN
        $tokenModel = new TokenModel();
        
        try {
            // El modelo TokenModel utiliza replace() para Insertar o Actualizar (UPSERT).
            $tokenModel->guardarToken($userId, $token);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Token registrado correctamente',
                'token' => substr($token, 0, 20) . '...'
            ]);
        } catch (\Exception $e) {
            // Manejo de errores de base de datos o modelo
             return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al guardar el token en la BD. Mensaje: ' . $e->getMessage()
            ]);
        }
    }
    public function verificarTokens() //agregado el 14/10/2025
    {
        $tokenModel = new TokenModel();
        
        // Obtener todos los tokens activos
        $tokens = $tokenModel->obtenerTokensActivos();
        
        $resultado = [
            'total_tokens' => count($tokens),
            'tokens' => []
        ];
        
        foreach ($tokens as $token) {
            $resultado['tokens'][] = [
                'id' => $token['id'],
                'user_id' => $token['user_id'],
                'token_preview' => substr($token['fcm_token'], 0, 20) . '...',
                'token_completo' => $token['fcm_token']
            ];
        }
        
        // Log para debugging
        log_message('debug', "Verificación de tokens - Total: " . count($tokens));
        
        return $this->response->setJSON($resultado);
    }
}

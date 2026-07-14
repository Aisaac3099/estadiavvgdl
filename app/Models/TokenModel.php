<?php namespace App\Models;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table = 'fcm_tokens';
    protected $allowedFields = ['user_id', 'fcm_token', 'activo'];
    protected $primaryKey = 'id';
    
    // ... (guardarToken y obtenerTokensActivos permanecen iguales) ...

    public function guardarToken($userId, $token)
{
    $data = [
        'user_id' => $userId,
        'fcm_token' => $token,
        'activo' => '1'
    ];
    
    // DEBUG
    log_message('debug', "=== 🔔 GUARDANDO TOKEN ===");
    log_message('debug', "🔔 UserID: " . $userId);
    log_message('debug', "🔔 Token: " . substr($token, 0, 20) . '...');
    
    // SOLUCIÓN DEFINITIVA: 
    // 1. Primero eliminar cualquier token existente para este usuario
    $this->where('user_id', $userId)->delete();
    
    // 2. Insertar el nuevo token
    $result = $this->insert($data);
    
    // 3. Verificar que se insertó
    if ($result) {
        log_message('debug', "✅ Token guardado EXITOSAMENTE para usuario: " . $userId);
    } else {
        log_message('error', "❌ Error al guardar token para usuario: " . $userId);
    }
    
    // 4. Debug: Contar tokens totales
    $totalTokens = $this->countAll();
    log_message('debug', "🔔 Total de tokens en BD: " . $totalTokens);
}
    public function obtenerTokensActivos()
    {
        return $this->select('id, fcm_token') // Añadimos 'id' para la limpieza
                    ->where('activo', 1)
                    ->findAll();
    }
    
    /**
     * Marca un token como inactivo o lo elimina basado en el token.
     * @param string $token El token FCM a desactivar.
     */
    public function desactivarPorToken(string $token)
    {
        // Alternativa 1: Borrar el registro (más simple)
        $this->where('fcm_token', $token)->delete();
        
        // Alternativa 2: Marcar como inactivo (si prefieres guardar el historial)
        /*
        $this->where('fcm_token', $token)
             ->set(['activo' => 0])
             ->update();
        */
        log_message('debug', "Token UNREGISTERED eliminado de la BD: " . substr($token, 0, 20) . '...');
    }
}

<?php namespace App\Controllers;

use App\Models\NotificacionLeidaModel;
use App\Models\NotificacionModel;
use Throwable;

class NotificacionController extends BaseController
{
    protected $notificacionModel;
    protected $notificacionLeidaModel;

    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
        $this->notificacionLeidaModel = new NotificacionLeidaModel();
    }

    public function leer($id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $notificacion = $this->notificacionModel->find($id);
        if (!$notificacion) {
            return redirect()->back();
        }

        try {
            $lecturaExistente = $this->notificacionLeidaModel
                ->where('notificacion_id', $id)
                ->where('usuario_id', $userId)
                ->first();

            if (!$lecturaExistente) {
                $this->notificacionLeidaModel->insert([
                    'notificacion_id' => $id,
                    'usuario_id' => $userId,
                    'leida_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (Throwable $e) {
            log_message('error', 'No se pudo registrar la lectura de la notificación {id} para el usuario {userId}: {error}', [
                'id' => $id,
                'userId' => $userId,
                'error' => $e->getMessage(),
            ]);
        }

        if ($notificacion['modulo'] === 'servicios_autos') {
            return redirect()->to(base_url('servicios_autos/detalles/' . $notificacion['referencia_id']));
        }

        if ($notificacion['modulo'] === 'servicios') {
            return redirect()->to(base_url('editServicio/' . $notificacion['referencia_id']));
        }

        return redirect()->to(base_url('agenda'));
    }
}

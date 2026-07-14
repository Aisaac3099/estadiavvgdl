<?php namespace App\Controllers;

use App\Models\NotificacionModel;

class NotificacionController extends BaseController
{
    protected $notificacionModel;
    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
    }
     public function leer($id){
        $notificacion = $this->notificacionModel->find($id);
        if(!$notificacion){
            return redirect()->back();
        }
        $this->notificacionModel->update($id, ['leida' => 1]);
        if ($notificacion ['modulo']==='servicios_autos')
            {
                return redirect()->to(base_url('servicios_autos/detalles/'. $notificacion['referencia_id']));
            }
            if ($notificacion ['modulo']==='servicios')
            {
                return redirect()->to(base_url('editServicio/'. $notificacion['referencia_id']));
            }
            return redirect()->to(base_url('agenda'));
     }
}
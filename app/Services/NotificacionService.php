<?php
namespace App\Services;

use App\Models\NotificacionModel;

class NotificacionService
{
    protected $notificacionModel;

    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
    }

    public function paraNavbar()
    {
        $userId = session()->get('user_id');

        //notificaciones guardadas en base de datos
        $persistidas = $this->persistidasNoLeidasBuilder($userId)
            ->orderBy('notificaciones.created_at', 'DESC')
            ->findAll();

        //Recordatorios dinamicos
        $dinamicas = array_merge(service('notificacionesServiciosAutos')->paraNavbar(),
        service('notificacionesAgenda')->paraNavbar());
        //adaptar las persistidas al mismo formato
        foreach($persistidas as &$notificacion){
            $notificacion['url'] = base_url('notificaciones/leer/' . $notificacion['id']);
            $notificacion['icono'] = 'fas fa-bell';
            $notificacion['color'] = 'primary';
            $notificacion['fecha'] = $notificacion['created_at'];
            $notificacion['dinamica'] = false;
            //debe conservarse el modulo que viene de la base de datos
            $notificacion['modulo'] = $notificacion['modulo'] ?? 'general';
        }
        //unir mbas listas
        $notificaciones = array_merge($dinamicas, $persistidas);
        //ordenarlas por fechas
        
        usort ($notificaciones, function ($a, $b){
            return strtotime ($b['fecha']) <=> strtotime($a['fecha']);
        });
        //mostrar maximo 20
        return array_slice($notificaciones, 0, 20);

    }

    public function contarParaNavbar(){
        $userId = session()->get('user_id');
        $persistidas = $this->persistidasNoLeidasBuilder($userId)->countAllResults();
        $dinamicas = service('notificacionesServiciosAutos')->contarParaNavbar() +
        service('notificacionesAgenda')->contarParaNavbar();
        return $persistidas + $dinamicas;
    }

    private function persistidasNoLeidasBuilder($userId)
    {
        $builder = $this->notificacionModel
            ->select('notificaciones.*')
            ->where('notificaciones.leida', 0);

        if (!$userId) {
            return $builder->where('1 = 0');
        }

        return $builder
            ->join(
                'notificaciones_leidas',
                'notificaciones_leidas.notificacion_id = notificaciones.id AND notificaciones_leidas.usuario_id = ' . $this->notificacionModel->db->escape($userId),
                'left'
            )
            ->where('notificaciones_leidas.id IS NULL');
    }

    
}

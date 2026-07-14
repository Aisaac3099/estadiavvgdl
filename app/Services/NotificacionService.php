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
        //notificaciones guardadas en base de datos
        $persistidas = $this->notificacionModel->where('leida',0)->orderBy('created_at', 'DESC')->findAll();

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
        //mostrar maximo 10
        return array_slice($notificaciones, 0, 10);

    }

    public function contarParaNavbar(){
        $persistidas = $this->notificacionModel->where('leida', 0)->countAllResults();
        $dinamicas = service('notificacionesServiciosAutos')->contarParaNavbar() +
        service('notificacionesAgenda')->contarParaNavbar();
        return $persistidas + $dinamicas;
    }

    
}
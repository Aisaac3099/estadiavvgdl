<?php
namespace App\Services;

class AgendaNotificacionService
{
    public function paraNavbar()
    {
       
        $agendasAtrasadas = $this->obtenerAgendasAtrasadas();
        $agendasHoy = $this->obtenerAgendaDeHoy();
        $notificaciones = [];
        foreach($agendasAtrasadas as $agenda){
            $notificaciones[] = $this->construirNotificacionAtrasada($agenda);
        }
        foreach ($agendasHoy as $agenda){
            $notificaciones[] = $this->construirNotificacionHoy($agenda);
        }
        return $notificaciones;
    }

    public function contarParaNavbar()
    {
        return count($this->paraNavbar());
    }
    private function obtenerAgendaDeHoy()
    {
        $db = \Config\Database::connect();

        $hoy = date('Y-m-d');

        return $db->table('agenda a')->select(
        'a.id,
        a.fechaInicio,
        a.prioridad,
        c.nombre as nombre_cliente,
        s.servicio as servicio_descripcion,
        s.estatusServ
        ')->join('cliente c', 'c.id = a.cliente')->join('servicios s', 's.id = a.servicio')
        ->where('DATE(a.fechaInicio)', $hoy)->whereNotIn('s.estatusServ',[3,5])
        ->get()->getResultArray();
    }
            private function construirNotificacionHoy($agenda)
            {
                $prioridad = strtolower ($agenda['prioridad']);

                if($prioridad === 'alta'){
                    return[
                        'id' => 'agenda-alta-' . $agenda['id'],
                        'titulo' => 'Servicio de prioridad alta para hoy',
                        'mensaje' => $agenda['nombre_cliente'] . ' - ' . $agenda['servicio_descripcion'],
                        'modulo' => 'agenda',
                        'url' => base_url('editAgenda/' . $agenda['id']),
                        'icono' => 'fas fa-exclamation-circle',
                        'color' => 'danger',
                        'fecha' => $agenda['fechaInicio'],
                        'dinamico' => true
                    ];
                }
                return [
                'id' => 'agenda-hoy-'. $agenda['id'], 
                'titulo'=> 'Servicio Agendado para hoy', 
                'mensaje' => $agenda['nombre_cliente'] . ' - ' . $agenda['servicio_descripcion'],
                'modulo' => 'agenda',
                'url' => base_url('editAgenda/' . $agenda['id']),
                'icono' => 'fas fa-calendar-day',
                'color' => 'info',
                'fecha' => $agenda['fechaInicio'],
                'dinamica' => true
                ];
    }
    private function obtenerAgendasAtrasadas()
    {
         $db = \Config\Database::connect();

        $hoy = date('Y-m-d');
        $desde =date('Y-m-d', strtotime('-15 days'));

        return $db->table('agenda a')->select(
        'a.id,
        a.fechaInicio,
        a.prioridad,
        c.nombre as nombre_cliente,
        s.servicio as servicio_descripcion,
        s.estatusServ
        ')->join('cliente c', 'c.id = a.cliente')->join('servicios s', 's.id = a.servicio')
        ->where('DATE(a.fechaInicio) >=', $desde)
        ->where('DATE(a.fechaInicio) <', $hoy)->whereNotIn('s.estatusServ',[3,5])
        ->get()->getResultArray();
    }

    private function construirNotificacionAtrasada($agenda){

    $hoy = date('Y-m-d');
    $diasAtraso = floor((strtotime($hoy) - strtotime(date('Y-m-d', strtotime($agenda['fechaInicio']))))/86400);

                return [
                'id' => 'agenda-atrasada-'. $agenda['id'], 
                'titulo'=> 'Servicio atrasado', 
                'mensaje' => $agenda['nombre_cliente'] . ' - ' . $agenda['servicio_descripcion'] . ' debio realizarse hace' . $diasAtraso . ' dias.', 
                'modulo' => 'agenda',
                'url' => base_url('editAgenda/' . $agenda['id']),
                'icono' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'fecha' => $agenda['fechaInicio'],
                'dinamica' => true
                ];
    }
}
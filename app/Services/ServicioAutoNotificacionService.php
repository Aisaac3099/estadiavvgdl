<?php
namespace App\Services;

class ServicioAutoNotificacionService
{
    public function paraNavbar()
    {
       
        $servicios = $this->obtenerServiciosConAlerta();
        $notificaciones = [];
        foreach ($servicios as $servicio){
            $notificaciones[] = $this->construirNotificacion($servicio);
        }
        return $notificaciones;
    }

    public function contarParaNavbar()
    {
        return count($this->paraNavbar());
    }
    private function obtenerServiciosConAlerta()
    {
        $db = \Config\Database::connect();

        $limite = date('Y-m-d', strtotime('+5 days'));

        return $db->table('servicios_autos sa')->select('sa.id,
        sa.tipo_servicio,
        sa.proximo_servicio,
        sa.tipo_registro,
        a.alias,
        a.placas,
        a.marca,
        a.modelo')->join('autos a', 'a.id = sa.auto_id')->where('sa.proximo_servicio IS NOT NULL', null, false)
        ->where('sa.proximo_servicio<=', $limite)->get()->getResultArray();
    }
private function construirNotificacion($servicio)
{
            $diasRestantes = $this->calcularDiasRestantes($servicio['proximo_servicio']);
            
            if ($diasRestantes < 0){
                return ['id' => 'servicio-vencido-'. $servicio['id'], 
                'titulo'=> 'Servicio Vencido', 
                'mensaje' => $servicio['alias']. ' - ' . $servicio['tipo_servicio'] . ' vencio hace ' . abs($diasRestantes) . ' dias.',
                'modulo' => 'servicios_autos',
                'url' => base_url('servicios_autos/detalles/' . $servicio['id']),
                'icono' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'fecha' => $servicio['proximo_servicio'],
                //'fecha_texto' => abs($diasRestantes) === 1 ? 'Vencido hace 1 dia' : 'Vencido hace ' . abs($diasRestantes) . ' dias',
                'dinamica' => true];
            } return[
                'id' => 'servicio-por-vencer-' . $servicio['id'],
                'titulo' => 'Servicio por Vencer',
                'mensaje' => $servicio['alias'] . ' - ' . $servicio['tipo_servicio'] . ' vence en ' . $diasRestantes . ' dias.',
                'modulo' => 'servicios_autos',
                'url' => base_url ('servicios_autos/detalles/' . $servicio['id']),
                'icono' => 'fas fa-clock',
                'color' => 'warning',
                'fecha' => $servicio['proximo_servicio'],
                //'fecha_texto' => abs($diasRestantes) === 0 ? 'Vence hoy' : ($diasRestantes === 1 ? 'Vence mañana' : 'Vence en ' . $diasRestantes . ' dias'),
                'dinamica' => true];
            
        }
        private function calcularDiasRestantes($fecha)
        {
            $hoy = date ('Y-m-d');
        return floor((strtotime($fecha)-strtotime($hoy))/86400);
    }
}
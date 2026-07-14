<?php namespace App\Controllers;

use App\Models\ServicioModel;

class ServiciosController extends BaseController
{
    public function verServiciosCliente($idCliente)
    {
        $servicioModel = new ServicioModel();

        $servicios = $servicioModel
            ->select('s.id as IdServicio, c.nombre as Cliente, s.servicio, es.estatus, es.etiquetas')
            ->from('servicios s')
            ->join('cliente c', 'c.id = s.cliente')
            ->join('estatusservicio es', 'es.id = s.estatusServ')
            ->where('c.id', $idCliente)
            ->groupBy('s.id')
            ->findAll();

        return view('servicios/verServiciosCliente', [
            'servicios' => $servicios,
            'idCliente' => $idCliente
        ]);
    }
}

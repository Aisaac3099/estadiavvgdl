<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventarioModel;
use App\Models\InventarioMovimientoModel;
use App\Models\TecnicoModel;

class InventarioMovimientoController extends BaseController
{
    public function __construct()
    {
        $this->inventarioModel = new InventarioModel();
        $this->movimientoModel = new InventarioMovimientoModel();
        $this->tecnicoModel = new TecnicoModel();
    }

    /**
     * Muestra el historial general de movimientos del inventario
     * 
     * obtiene los elementos activos, los tecnicos registrados y los movimientos realizados junto con sus
     * datos relacionados
     * 
     */
    public function index()
    {
        $inventario = $this->inventarioModel->where('activo', 1)->orderBy('nombre', 'ASC')->findAll();
        //recibir el elemento enviado desde la vista de edicion
        $inventarioSeleccionado = (int) $this->request->getGet('inventario_id');

        if(! in_array($inventarioSeleccionado, array_column($inventario, 'id'))){
            $inventarioSeleccionado = 0;
        }
        $tecnicos = $this->tecnicoModel->orderBy('nombre', 'ASC')->findAll();
        $movimientos = $this->movimientoModel->select(
            'inventario_movimientos.*,
            inventario.nombre AS inventario_nombre,
            inventario.modelo AS inventario_modelo,
            inventario.alias AS inventario_alias,
            tecnicos.nombre AS tecnico_nombre,
            usuarios.nombre AS usuario_nombre'
        )->join(
            'inventario',
            'inventario.id = inventario_movimientos.inventario_id'
        )->join(
            'tecnicos',
            'tecnicos.id = inventario_movimientos.tecnico_id',
            'left'
        )
        ->join('usuarios', 'usuarios.id = inventario_movimientos.usuario_id', 'left'
        )->orderBy('inventario_movimientos.fecha_movimiento', 'DESC')->orderBy('inventario_movimientos.id', 'DESC')
        ->findAll();

        $totalMovimientos = count($movimientos);

        $totalPerdidos = count(array_filter($movimientos, static fn($movimiento) => 
        $movimiento['tipo_movimiento'] === 'perdido'));
        $totalSinRegreso = count(array_filter($movimientos, static fn($movimiento) =>
        $movimiento['tipo_movimiento'] === 'sin_regreso'));

        //calcular los prestamos que todavia no han sido devueltos con fecha mas antigua
        $prestamosAbiertos = [];
        $movimientosCronologicos = array_reverse($movimientos);
        foreach($movimientosCronologicos as $movimiento){
            if(! in_array($movimiento['tipo_movimiento'],['prestamo', 'devolucion', 'perdido', 'sin_regreso'], true)
                || empty($movimiento['tecnico_id'])){
            continue;
            }
            $clave = $movimiento['inventario_id'] . '-' . $movimiento['tecnico_id'];
            if (! isset($prestamosAbiertos[$clave])){
                $prestamosAbiertos[$clave] = [
                    'inventario_id' => (int) $movimiento['inventario_id'],
                    'tecnico_id' => (int) $movimiento['tecnico_id'],
                    'inventario_nombre' => $movimiento['inventario_nombre'],
                    'inventario_modelo' => $movimiento['inventario_modelo'],
                    'inventario_alias' => $movimiento['inventario_alias'],
                    'tecnico_nombre' => $movimiento['tecnico_nombre'],
                    'prestamos' => []
                ];
            }

            if($movimiento['tipo_movimiento'] === 'prestamo'){
                $prestamosAbiertos[$clave]['prestamos'][] = [
                    'cantidad' => (int) $movimiento['cantidad'],
                    'fecha_movimiento' => $movimiento['fecha_movimiento']
                ];
                continue;
            }
            $cantidadPorCerrar = (int) $movimiento['cantidad'];
            while($cantidadPorCerrar > 0 && ! empty($prestamosAbiertos[$clave]['prestamos'])){
                $cantidadPrestada = (int) $prestamosAbiertos[$clave]['prestamos'][0]['cantidad'];
                if($cantidadPorCerrar >= $cantidadPrestada){
                    $cantidadPorCerrar -= $cantidadPrestada;

                    array_shift($prestamosAbiertos[$clave]['prestamos']);
                }else{
                    $prestamosAbiertos[$clave]['prestamos'][0]['cantidad']-=$cantidadPorCerrar;
                    $cantidadPorCerrar = 0;
                }
            }
        }
        $prestamosPendientes = [];
        foreach($prestamosAbiertos as $prestamoAbierto){
            if(empty($prestamoAbierto['prestamos'])){
                continue;
            }
            $cantidadPendiente = array_sum(
                array_column($prestamoAbierto['prestamos'],'cantidad')
            );
            if($cantidadPendiente <= 0){
                continue;
            }
            $fechaPrestamo = $prestamoAbierto['prestamos'][0]['fecha_movimiento'];
            $segundosTranscurridos = time() - strtotime($fechaPrestamo);

            $prestamosPendientes[] = [
                'inventario_id' => (int) $prestamoAbierto['inventario_id'],
                    'tecnico_id' => (int) $prestamoAbierto['tecnico_id'],
                    'inventario_nombre' => $prestamoAbierto['inventario_nombre'],
                    'inventario_modelo' => $prestamoAbierto['inventario_modelo'],
                    'inventario_alias' => $prestamoAbierto['inventario_alias'],
                    'tecnico_nombre' => $prestamoAbierto['tecnico_nombre'],
                    'cantidad_pendiente' => $cantidadPendiente,
                    'fecha_prestamo' => $fechaPrestamo,
                    'dias_transcurridos' => max(0, (int)floor($segundosTranscurridos / 86400))
            ];
        }


        return view('inventario/movimientos', [
            'inventario' => $inventario,
            'tecnicos' => $tecnicos,
            'movimientos' => $movimientos,
            'prestamosPendientes' => $prestamosPendientes,
            'inventarioSeleccionado' => $inventarioSeleccionado,
            'totalMovimientos' => $totalMovimientos,
            'totalPerdidos' => $totalPerdidos,
            'totalSinRegreso' => $totalSinRegreso
        ]);
    }

    /**
     * 
     * Registra un movimiento y actualiza las existencias del inventario
     * 
     */
    public function store()
    {
        $reglas = [
            'inventario_id' => 'required|integer|is_not_unique[inventario.id]',
            'tipo_movimiento' => 
            'required|in_list[entrada,salida,prestamo,devolucion,ajuste_entrada,ajuste_salida,perdido,sin_regreso]',
            'cantidad' => 'required|integer|greater_than[0]',
            'tecnico_id' => 'permit_empty|integer|is_not_unique[tecnicos.id]',
            'fecha_movimiento' => 'required'
        ];

        if (! $this->validate($reglas)){
            return redirect()
            ->back()
            ->withInput()
            ->with('error', implode('<br>', $this->validator->getErrors()));
        }
        $inventarioId = (int)
        $this->request->getPost('inventario_id');
        $tipoMovimiento = (string)
        $this->request->getPost('tipo_movimiento');
        $cantidad = (int)
        $this->request->getPost('cantidad');
        $tecnicoRecibido = trim((string)
        $this->request->getPost('tecnico_id'));
        $tecnicoId = $tecnicoRecibido === '' ? null : (int)$tecnicoRecibido;
        //identificar al usuario que registra el movimiento
        $usuarioId = (int) session()->get('user_id');
        if($usuarioId <= 0){
            return redirect()->back()->withInput()->with('error', 
            'No fue posible identificar al usuario que registra el movimiento');
        }

        $item = $this->inventarioModel->find($inventarioId);
        if (! $item || (int) $item['activo'] !== 1){
            return redirect()->back()
            ->withInput()
            ->with('error', 'El elemento seleccionado no existe o se encuentra dado de baja');
        }
        //validar movimientos segun el tipo de control
        $movimientosPermitidos = $item['tipo_control'] === 'retornable' 
        ? ['entrada', 'salida', 'prestamo', 'devolucion', 'ajuste_entrada', 'ajuste_salida', 'perdido', 'sin_regreso']
        :['entrada', 'salida', 'ajuste_entrada', 'ajuste_salida'];
        if (! in_array($tipoMovimiento, $movimientosPermitidos, true)){
            return redirect()->back()->withInput()->with('error', 'El movimiento seleccionado no corresponde  al tipo de control de elemento');
        }

        if (in_array($tipoMovimiento, ['prestamo', 'devolucion', 'perdido', 'sin_regreso' ], true) && $tecnicoId === null){
            return redirect()->back()->withInput()->with('error', 
            'Debe seleccionar un tecnico para registrar prestamo o devoluciones');
        }

        //comprobar que la devolucion corresponda a un prestamo pendiente
        if(in_array($tipoMovimiento, ['devolucion', 'perdido', 'sin_regreso'], true)){
            $cantidadPendiente = $this->cantidadPendientePrestamo($inventarioId, $tecnicoId);
            if($cantidadPendiente <= 0){
                return redirect()->back()->withInput()->with('error', 'El tecnico no tiene unidades pendientes de este elemento');
            }
            if($cantidad > $cantidadPendiente){
                return redirect()->back()->withInput()->with('error', 
                'La cantidad indicada supera la cantidad pendiente del tecnico.');
            }
        }
        $existenciaAnterior = (int) $item['cantidad'];
        //diferenciar movimientos que aumentan, disminuyen o conservan la existencia

        $movimientosQueAumentan = [
            'entrada',
            'devolucion',
            'ajuste_entrada'
        ];
        $movimientosQueDisminuyen = [
            'salida',
            'prestamo',
            'ajuste_salida'
        ];

        if(in_array($tipoMovimiento, $movimientosQueAumentan, true)){
            $existenciaResultante = $existenciaAnterior + $cantidad;
        }elseif(in_array($tipoMovimiento, $movimientosQueDisminuyen, true)){
            $existenciaResultante = $existenciaAnterior - $cantidad;
        }else{
            $existenciaResultante = $existenciaAnterior;
        }

        if($existenciaResultante < 0){
            return redirect()->back()->withInput()->with('error', 'No hay existencias suficientes. La cantidad disponible es ' . $existenciaAnterior);
        }
        $fechaRecibida = trim((string) $this->request->getPost('fecha_movimiento'));
        $fechaMovimiento = str_replace('T', ' ', $fechaRecibida);
        if (strlen($fechaMovimiento) === 16){
            $fechaMovimiento .= ':00';
        }
        $observaciones = trim((string) $this->request->getPost('observaciones'));
        //exigir una justificacion al corregir existencias
        if(in_array($tipoMovimiento,['ajuste_entrada', 'ajuste_salida','perdido', 'sin_regreso'], true) && $observaciones === ''){
            return redirect()->back()->withInput()->with('error', 
            'Debe escribir el motivo del ajuste de existencias');
        }
        
        $datosMovimiento = [
            'inventario_id' => $inventarioId,
            'tecnico_id' => $tecnicoId,
            'usuario_id' => $usuarioId,
            'tipo_movimiento' => $tipoMovimiento,
            'cantidad' => $cantidad,
            'existencia_anterior' => $existenciaAnterior,
            'existencia_resultante' =>$existenciaResultante,
            'fecha_movimiento' => $fechaMovimiento,
            'observaciones' => $observaciones === '' ? null : $observaciones
        ];
        $db = db_connect();
        $db->transBegin();

        $movimientoGuardado = $this->movimientoModel->insert($datosMovimiento);
        $inventarioActualizado = $this->inventarioModel->update($inventarioId,['cantidad' => $existenciaResultante]);

        if(! $movimientoGuardado || ! $inventarioActualizado || $db->transStatus() === false){
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No fue posible registrar el movimiento');
        }
        $db->transCommit();

        return redirect()->to(base_url('inventario/movimientos'))->with('success', 
        'Movimiento Registrado Correctamente');
    }


    /**
     * 
     * calcular cuantas unidades de un elemento conserva pendientes por devolver un tecnico
     * 
     */
    private function cantidadPendientePrestamo(int $inventarioId, int $tecnicoId): int{
        $prestamos = $this->movimientoModel->selectSum('cantidad', 'total')
        ->where('inventario_id', $inventarioId)->where('tecnico_id', $tecnicoId)->where('tipo_movimiento', 'prestamo')
        ->first();
        //considerar todos los movimientos que cierran prestamos pendientes
        $movimientosCerrados = $this->movimientoModel->selectSum('cantidad', 'total')
        ->where('inventario_id', $inventarioId)
        ->where('tecnico_id', $tecnicoId)
        ->whereIn('tipo_movimiento',['devolucion', 'perdido', 'sin_regreso'])->first();

        $totalPrestado = (int) ($prestamos['total'] ?? 0);
        $totalCerrado = (int) ($movimientosCerrados['total'] ?? 0);

        return max(0, $totalPrestado - $totalCerrado);
    }
}

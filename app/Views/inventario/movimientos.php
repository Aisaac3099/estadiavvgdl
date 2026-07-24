<?= $this->include('layouts/headerListas') ?>
<link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<?php
$mostrar = static fn($valor) =>
    trim((string) ($valor ?? '')) !== '' ? esc($valor) : '—';
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('error')):?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?=session()->getFlashdata('error')?>
                    <button type="button"
                    class="close"
                    data-dismiss="alert">
                <span>&times;</span>
            </button>
                </div>
                <?php endif;?>
                 <?php if (session()->getFlashdata('success')):?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?=session()->getFlashdata('success')?>
                    <button type="button"
                    class="close"
                    data-dismiss="alert">
                <span>&times;</span>
            </button>
                </div>
                <?php endif;?>
            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Movimientos de inventario</h1>
                </div>
                 

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('agenda') ?>">Inicio</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="<?= base_url('inventario') ?>">Inventario</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Movimientos
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
             <!-- mostrar los articulos pendientes de devolucion -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding mr-2"></i>
                        Prestamos Pendientes
                    </h3>
                    <span class="badge  badge-warning float-right">
                        <?=count($prestamosPendientes ?? [])?>
                    </span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Elemento</th>
                                <th>Modelo</th>
                                <th>Alias</th>
                                <th>Tecnico responsable</th>
                                <th>Cantidad Pendiente</th>
                                <th>Prestado desde</th>
                                <th>Dias Transcurridos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($prestamosPendientes)):?>
                            <?php foreach($prestamosPendientes as $prestamo):?>
                                <tr>
                                    <td>
                                        <?=esc($prestamo['inventario_nombre'])?>
                                    </td>
                                    <td>
                                        <?=esc($prestamo['inventario_modelo'])?>
                                    </td>
                                    <td>
                                        <?=$mostrar($prestamo['inventario_alias'])?? null ?>
                                    </td>
                                    <td>
                                        <?=esc($prestamo['tecnico_nombre'])?>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                        <?=esc($prestamo['cantidad_pendiente'])?>
                                        </span>
                                    </td>

                                    <td>
                                        <?=date('d/m/Y H:i:s', strtotime($prestamo['fecha_prestamo']))?>
                                    </td>

                                    <td>
                                        <span class="badge badge-secondary">
                                        <?=esc($prestamo['dias_transcurridos'])?> dia(s)
                                    </span>
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-success btnRegistrarDevolucion"
                                            data-inventario-id="<?=esc($prestamo['inventario_id'])?>"
                                            data-tecnico-id="<?=esc($prestamo['tecnico_id'])?>"
                                            data-cantidad-pendiente="<?=esc($prestamo['cantidad_pendiente'])?>">
                                                <i class="fas fa-undo-alt mr-1"></i>
                                                Registrar Devolucion
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="sr-only">Mostrar Opciones</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button type="button" class="dropdown-item btnCerrarPrestamo"
                                                data-tipo-movimiento="perdido"
                                                data-inventario-id="<?=esc($prestamo['inventario_id'])?>"
                                                data-tecnico-id="<?=esc($prestamo['tecnico_id'])?>"
                                                data-cantidad-pendiente="<?=esc($prestamo['cantidad_pendiente'])?>">
                                                <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                                                Marcar como perdido
                                                </button>
                                                <button type="button" class="dropdown-item btnCerrarPrestamo"
                                                data-tipo-movimiento="sin_regreso"
                                                data-inventario-id="<?=esc($prestamo['inventario_id'])?>"
                                                data-tecnico-id="<?=esc($prestamo['tecnico_id'])?>"
                                                data-cantidad-pendiente="<?=esc($prestamo['cantidad_pendiente'])?>">
                                                <i class="fas fa-ban text-warning mr-2"></i>
                                                Marcar sin regreso
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No existen prestamos pendientes de devolucion
                                        </td>
                                    </tr>
                                    <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- filtrar rapidamente los movimientos sin registro y perdidos-->
             <h5 class="mb-2">Estado de los movimientos</h5>
             <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-info filtro-movimiento-card"
                    data-movimiento=""
                    style="cursor: pointer;">
                <div class="inner"><h3><?=esc($totalMovimientos)?></h3>
            <p>Todos los movimientos</p>
        </div>
        <div class="icon">
            <i class="fas fa-list"></i>
        </div>
    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="small-box bg-danger filtro-movimiento-card"
                    data-movimiento="perdido"
                    style="cursor: pointer;">
                <div class="inner"><h3><?=esc($totalPerdidos)?></h3>
            <p>Perdidos</p>
        </div>
        <div class="icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="small-box bg-warning filtro-movimiento-card"
                    data-movimiento="sin_regreso"
                    style="cursor: pointer;">
                <div class="inner"><h3><?=esc($totalSinRegreso)?></h3>
            <p>Sin Regreso</p>
        </div>
        <div class="icon">
            <i class="fas fa-ban"></i>
        </div>
    </div>
                </div>
                
             </div>

            <!-- mostrar el historial de entradas, salidas, préstamos y devoluciones -->
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Historial de movimientos
                    </h3>
                    <button type="button"
                            class="btn btn-primary float-right"
                            data-toggle="modal"
                            data-target="#modalMovimiento">
                        <i class="fas fa-exchange-alt"></i>
                            Registrar Movimiento
                    </button>
                </div>

                <div class="card-body table-responsive">

                    <table id="example1"
                           class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Elemento</th>
                                <th>Modelo</th>
                                <th>Alias</th>
                                <th>Técnico</th>
                                <th>Movimiento</th>
                                <th>Registrado por</th>
                                <th>Cantidad</th>
                                <th>Existencia anterior</th>
                                <th>Existencia resultante</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>

                        <tbody>
                                <?php
                                //identificar con colores cada tipo de movimiento
                                 $estilosMovimientos = [
                                    'entrada' => ['Entrada', 'badge-success'],
                                    'salida' => ['Salida', 'badge-secondary'],
                                    'prestamo' => ['Prestamo', 'badge-primary'],
                                    'devolucion' => ['Devolucion', 'badge-success'],
                                    'ajuste_entrada' => ['Ajuste de entrada', 'badge-info'],
                                    'ajuste_salida' => ['Ajuste de salida', 'badge-warning'],
                                    'perdido' => ['Perdido', 'badge-danger'],
                                    'sin_regreso' => ['Sin regreso', 'badge-warning'],
                                 ];?>
                            <?php foreach ($movimientos as $movimiento): ?>
                                <tr>
                                    <td>
                                        <?= date(
                                            'd/m/Y H:i:s',
                                            strtotime($movimiento['fecha_movimiento'])
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= esc($movimiento['inventario_nombre']) ?>
                                    </td>

                                    <td>
                                        <?= $mostrar($movimiento['inventario_modelo']) ?>
                                    </td>

                                    <td>
                                        <?= $mostrar($movimiento['inventario_alias'] ?? null)?>
                                    </td>

                                    <td>
                                        <?= $mostrar($movimiento['tecnico_nombre']) ?>
                                    </td>

                                    <?php
                                    $estiloMovimiento = $estilosMovimientos[
                                        $movimiento['tipo_movimiento']
                                    ]??[ucfirst(str_replace('_',' ',$movimiento['tipo_movimiento'])),'badge-secondary'];?>
                                    <td data-search="<?= esc($movimiento['tipo_movimiento'])?>">
                                        <span class="badge <?= esc($estiloMovimiento[1])?>">
                                            <?= esc($estiloMovimiento[0])?>
                                        </span>
                                    </td>

                                    <td>
                                        <?=$mostrar($movimiento['usuario_nombre'] ?? null)?>
                                    </td>

                                    <td>
                                        <?= esc($movimiento['cantidad']) ?>
                                    </td>

                                    <td>
                                        <?= esc($movimiento['existencia_anterior']) ?>
                                    </td>

                                    <td>
                                        <?= esc($movimiento['existencia_resultante']) ?>
                                    </td>

                                    <td>
                                        <?= $mostrar($movimiento['observaciones']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </section>

</div>

<!-- formulario para registrar movimientos de existencias -->
<div class="modal fade" id="modalMovimiento" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form action="<?= base_url('inventario/movimientos/guardar') ?>"
              method="post"
              id="formMovimiento"
              class="modal-content">

            <?= csrf_field() ?>

            <div class="modal-header">
                <h5 class="modal-title">Registrar movimiento</h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Elemento del inventario</label>

                        <select name="inventario_id"
                                id="inventarioMovimiento"
                                class="form-control select2"
                                style="width: 100%"
                                data-placeholder="Escriba el nombre, alias o modelo"
                                required>
                            <option value="" disabled selected>
                                Seleccione un elemento
                            </option>

                            <?php foreach ($inventario as $item): ?>
                                <option value="<?= esc($item['id']) ?>"
                                data-tipo-control="<?=esc($item['tipo_control'])?>">
                                    <?= esc($item['nombre']) ?>
                                    <?=!empty($item['alias'])
                                        ? ' - Alias: ' . esc($item['alias'])
                                        : ''?>
                                    <?= !empty($item['modelo'])
                                        ? ' - Modelo: ' . esc($item['modelo'])
                                        : '' ?>
                                    (Existencia: <?= esc($item['cantidad']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Tipo de movimiento</label>

                        <select name="tipo_movimiento"
                                id="tipoMovimiento"
                                class="form-control"
                                required
                                disabled>
                            <option value="" disabled selected>
                                Seleccione un movimiento
                            </option>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                            <option value="ajuste_entrada">Ajuste de entrada</option>
                            <option value="ajuste_salida">Ajuste de salida</option>
                            <option value="prestamo">Préstamo</option>
                            <option value="devolucion">Devolución</option>
                            <option value="perdido">Perdido</option>
                            <option value="sin_regreso">Sin Regreso</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Técnico relacionado <span id="tecnicoObligatorio" class="text-danger d-none">*</span></label>

                        <select name="tecnico_id" id="tecnicoMovimiento" class="form-control">
                            <option value="">Sin técnico relacionado</option>

                            <?php foreach ($tecnicos as $tecnico): ?>
                                <option value="<?= esc($tecnico['id']) ?>">
                                    <?= esc($tecnico['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Cantidad</label>

                        <input type="number"
                                id="cantidadMovimiento"
                               name="cantidad"
                               class="form-control"
                               min="1"
                               required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Fecha y hora</label>

                        <input type="datetime-local"
                               name="fecha_movimiento"
                               class="form-control"
                               value="<?= date('Y-m-d\TH:i:s') ?>"
                               required>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Observaciones<span id="observacionesObligatorias" class="text-danger d-none">*</span></label>

                        <textarea id="observacionesMovimiento"
                                  name="observaciones"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Motivo, estado del artículo o información adicional"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit" class="btn btn-primary">
                    Guardar movimiento
                </button>
            </div>

        </form>
    </div>
</div>


<script defer src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const inventario = document.getElementById('inventarioMovimiento');
        const movimiento = document.getElementById('tipoMovimiento');
        const tecnico = document.getElementById('tecnicoMovimiento');
        const tecnicoObligatorio = document.getElementById('tecnicoObligatorio');
        const inventarioSeleccionado = <?= json_encode((string) ($inventarioSeleccionado ?? 0))?>;
        const cantidadMovimiento = document.getElementById('cantidadMovimiento');
        const observacionesMovimiento = document.getElementById('observacionesMovimiento');
        const observacionesObligatorias = document.getElementById('observacionesObligatorias');
        const formMovimiento = document.getElementById('formMovimiento');
        if(!inventario || !movimiento || !tecnico || !tecnicoObligatorio || !cantidadMovimiento
            || !observacionesMovimiento || !observacionesObligatorias || !formMovimiento
        ){
            return
        }
        
        //configurar Select2 dentro del modal de movimientos
        $('#modalMovimiento').on('shown.bs.modal', function () {
            const selectorInventario = $('#inventarioMovimiento');

            if (selectorInventario.hasClass('select2-hidden-accessible')) {
                selectorInventario.select2('destroy');
            }
            

            selectorInventario.select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalMovimiento'),
                placeholder: 'Escriba el nombre, alias o modelo',
                width: '100%'
            });

            if(inventarioSeleccionado > 0){
                selectorInventario.val(inventarioSeleccionado).trigger('change');
            }
        });
       

        function actualizarMovimientos(){
            const opcionSeleccionada = inventario.options[inventario.selectedIndex];
            const tipoControl = (opcionSeleccionada.dataset.tipoControl || '')
            .trim()
            .toLowerCase();
            movimiento.disabled = tipoControl === '';

            const opciones = movimiento.querySelectorAll('option[value]');
            opciones.forEach(function(opcion){
                const esMovimientoRetornable = [
                    'prestamo', 'devolucion', 'perdido', 'sin_regreso'
                ].includes(opcion.value);
                const deshabilitar = tipoControl === 'consumible' && esMovimientoRetornable;

                opcion.disabled = deshabilitar;
                opcion.hidden = deshabilitar;
            });
            if(tipoControl ==='consumible' && ['prestamo', 'devolucion', 'perdido', 'sin_regreso'
            ].includes(movimiento.value))
            {
                movimiento.value = '';
            }
            actualizarTecnico();
        }
        function actualizarTecnico(){
            const requiereTecnico = [
                'prestamo', 'devolucion', 'perdido', 'sin_regreso'
            ].includes(movimiento.value);

            tecnico.required = requiereTecnico;
            tecnicoObligatorio.classList.toggle('d-none', !requiereTecnico);
        }

        function actualizarObservaciones(){
            const requiereObservaciones = [
                'ajuste_entrada',
                'ajuste_salida',
                'perdido',
                'sin_regreso'
            ].includes(movimiento.value);
            observacionesMovimiento.required = requiereObservaciones;
            observacionesObligatorias.classList.toggle('d-none', !requiereObservaciones);
            observacionesMovimiento.setAttribute('placeholder', requiereObservaciones ? 'Explique obligatoriamente el motivo del movimiento'
            : 'Motivo, estado del articulo o informacion adicional');
        }

        $('#inventarioMovimiento').on('change', actualizarMovimientos);
        movimiento.addEventListener('change', actualizarTecnico);
        movimiento.addEventListener('change', actualizarObservaciones);
        actualizarMovimientos();

        if(inventarioSeleccionado > 0){
            $('#modalMovimiento').modal('show');
        }

        //preparar una devolucion desde los prestamos pendientes
        function abrirModalCierrePrestamo(boton, tipoMovimiento){
            const inventarioId = boton.dataset.inventarioId;
            const tecnicoId = boton.dataset.tecnicoId;
            const cantidadPendiente = boton.dataset.cantidadPendiente;

            inventario.value = inventarioId;
            actualizarMovimientos();

            movimiento.value = tipoMovimiento;
            tecnico.value = tecnicoId;
            cantidadMovimiento.value = cantidadPendiente;
            observacionesMovimiento.value = '';

            actualizarTecnico();
            actualizarObservaciones();

            $('#modalMovimiento').one('shown.bs.modal', function(){
                $('#inventarioMovimiento')
                .val(inventarioId)
                .trigger('change');
                movimiento.value = tipoMovimiento;
                tecnico.value = tecnicoId;
                cantidadMovimiento.value = cantidadPendiente;

                actualizarTecnico();
                actualizarObservaciones();
            });
            $('#modalMovimiento').modal('show');
        }
        document.querySelectorAll('.btnRegistrarDevolucion').forEach(function (boton){
            boton.addEventListener('click', function(){
                abrirModalCierrePrestamo(boton, 'devolucion');
            });
        });
        document.querySelectorAll('.btnCerrarPrestamo').forEach(function (boton){
            boton.addEventListener('click', function(){
                abrirModalCierrePrestamo(boton, boton.dataset.tipoMovimiento);
            });
        });

        formMovimiento.addEventListener('submit', function(evento){
            const requiereConfirmacion = [
                'perdido',
                'sin_regreso'
            ].includes(movimiento.value);
            
            if(!requiereConfirmacion){
                return;
            }
            const nombreMovimiento = movimiento.value === 'perdido' ? 'marcar como perdido' : 'marcar sin regreso';

            const cantidad = cantidadMovimiento.value;

            const confirmado = confirm('¿Esta seguro de ' + nombreMovimiento + ' ' + cantidad + ' unidad(es)? ' +
                'Este movimiento cerrara esa cantidad pendiente ' + 'y no aumentara la existencia disponible.'
            );

            if (!confirmado){
                evento.preventDefault();
            }
        });

        const tarjetasFiltroMovimiento = document.querySelectorAll('.filtro-movimiento-card');
        tarjetasFiltroMovimiento.forEach(function(tarjeta){
            tarjeta.addEventListener('click', function (){
                if(!$.fn.DataTable.isDataTable('#example1')){
                    return;
                }
                const tablaMovimientos = $('#example1').DataTable();
                const movimientoFiltrado = tarjeta.dataset.movimiento;

                tarjetasFiltroMovimiento.forEach(function(otraTarjeta){
                    otraTarjeta.classList.remove('elevation-3');
                });
                tarjeta.classList.add('elevation-3');
                if(movimientoFiltrado === ''){
                    tablaMovimientos
                    .column(5)
                    .search('')
                    .draw();
                    return;
                }
                const filtroSeguro = $.fn.dataTable.util.escapeRegex(movimientoFiltrado);
                tablaMovimientos.column(5).search('^' + filtroSeguro + '$', true, false).draw();
            });
        });
    });
</script>

<?= $this->include('layouts/footerListas') ?>
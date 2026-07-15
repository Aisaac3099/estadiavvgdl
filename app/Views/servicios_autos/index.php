<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>   

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Servicios de Autos</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Servicios de Autos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENIDO -->
    <section class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('error')):?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error')?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')):?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success')?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            <?php endif;?>
            <h5 class="mb-2">Estado del próximo servicio</h5>
            <div class="row"><!--Inicio Vista de estados-->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info filtro-card" data-estado="" style="cursor:pointer;">
                        <div class="inner">
                            <h3><?= $totalServicios?></h3>
                            <p>Todos</p>
                        </div>
                        <div class="icon">
                            <i class= "fas fa-list"></i>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-3 col-6">
                    <div class="small-box bg-success filtro-card" data-estado="Vigente" style="cursor:pointer;">
                        <div class="inner">
                            <h3><?= $vigentes?></h3>
                            <p>Vigentes</p>
                        </div>
                        <div class="icon">
                            <i class= "fas fa-check"></i>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning filtro-card" data-estado="Por Vencer" style="cursor:pointer;">
                        <div class="inner">
                            <h3><?= $porVencer?></h3>
                            <p>Por Vencer</p>
                        </div>
                        <div class="icon">
                            <i class= "fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger filtro-card" data-estado="Vencido" style="cursor:pointer;">
                        <div class="inner">
                            <h3><?= $vencidos?></h3>
                            <p>Vencidos</p>
                        </div>
                        <div class="icon">
                            <i class= "fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                </div><!-- Fin vista de estados-->
            <h5 class="mb-2">Estado del servicio</h5>
            <div class="row"><!--Inicio Vista de estados del servicio-->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info filtro-servicio-card" data-estado-servicio="material_comprado" style="cursor:pointer;">
                        <div class="inner"><h3><?= $materialComprado ?></h3><p>Material comprado</p></div>
                        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning filtro-servicio-card" data-estado-servicio="en_proceso" style="cursor:pointer;">
                        <div class="inner"><h3><?= $enProceso ?></h3><p>En proceso</p></div>
                        <div class="icon"><i class="fas fa-tools"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success filtro-servicio-card" data-estado-servicio="realizado" style="cursor:pointer;">
                        <div class="inner"><h3><?= $realizados ?></h3><p>Realizados</p></div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-dark filtro-servicio-card" data-estado-servicio="cancelado" style="cursor:pointer;">
                        <div class="inner"><h3><?= $cancelados ?></h3><p>Cancelados</p></div>
                        <div class="icon"><i class="fas fa-ban"></i></div>
                    </div>
                </div>
            </div><!-- Fin vista de estados del servicio-->
            <div class="row">
                <div class="col-12">

                    <div class="card">

                        <!-- HEADER CARD -->
                        <div class="card-header">
                            <h3 class="card-title">Historial de Servicios</h3>

                            <div class="float-sm-right"><!--  Botones se moveran a su propia vista
                                <button class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalAuto">
                                    Agregar Auto
                                </button>

                                <a href="<?= base_url('autos')?>"
                                class="btn btn-info btn-sm">
                                Mostrar Autos
                                </a>-->

                                <button class="btn btn-success btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalServicio">
                                    Agregar Servicio
                                </button>
                            </div>
                        </div>

                        <!-- BODY TABLE -->
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>Auto</th>
                                        <th>Tipo de Registro</th>
                                        <th>Categoria</th>
                                        <th>Tipo Servicio</th>
                                        <th>Descripción</th>
                                        <th>Fecha Servicio</th>
                                        <th>Estado del Servicio</th>
                                        <th>Próximo Servicio</th>
                                        <th>Estado Próximo Servicio</th>
                                        <th>EstadoFiltro</th>
                                        <th>EstadoServicioFiltro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if (!empty($servicios)): ?>

                                        <?php foreach ($servicios as $servicio): ?>
                                            <tr>
                                                <td>
                                                     <?= esc($servicio['alias']) ?> - 
                                                    <?= esc($servicio['marca']) ?>
                                                    <?= esc($servicio['modelo']) ?>
                                                    (<?= esc($servicio['placas']) ?>)
                                                </td>
                                                <td>
                                                <?php if($servicio['tipo_registro'] == 'unico'): ?>
                                                    <span class="badge badge-success">
                                                        Único
                                                    </span>
                                                <?php elseif($servicio['tipo_registro'] == 'periodico'): ?>
                                                    <span class="badge badge-primary">
                                                        Periódico
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">
                                                        Por Asignar
                                                    </span>
                                                <?php endif; ?>
                                                </td>
                                                <td><?= esc($servicio['categoria_servicio']) ?></td>
                                                <td><?= esc($servicio['tipo_servicio']) ?></td>
                                                <td><?= esc($servicio['descripcion']) ?></td>
                                                <td><?= esc($servicio['fecha_servicio']) ?></td>
                                                <td>
                                                    <?php
                                                        $estadoLabels = [
                                                            'material_comprado' => ['Material comprado', 'badge-info'],
                                                            'en_proceso' => ['En proceso', 'badge-warning'],
                                                            'realizado' => ['Realizado', 'badge-success'],
                                                            'cancelado' => ['Cancelado', 'badge-dark'],
                                                        ];
                                                    ?>
                                                    <?php if ($servicio['tipo_registro'] == 'por_asignar'): ?>
                                                        <span class="badge badge-warning">Por asignar</span>
                                                    <?php elseif (!empty($servicio['estado_servicio']) && isset($estadoLabels[$servicio['estado_servicio']])): ?>
                                                        <span class="badge <?= $estadoLabels[$servicio['estado_servicio']][1] ?>"><?= $estadoLabels[$servicio['estado_servicio']][0] ?></span>
                                                        <?php if (!empty($servicio['observacion_estado'])): ?>
                                                            <small class="d-block text-muted mt-1"><?= esc($servicio['observacion_estado']) ?></small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Sin estado registrado</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($servicio['proximo_servicio'])): ?>
                                                        <span class="badge badge-secondary">No aplica</span>
                                                    <?php else: ?>
                                                        <?= date('d/m/Y', strtotime($servicio['proximo_servicio'])) ?>
                                                    <?php endif; ?>
                                                </td>


                                                <!--<td><?php $hoy = date('Y-m-d');?>
                                                    <?php $diasRestantes = floor((strtotime($servicio['proximo_servicio'])-strtotime($hoy))/86400);?>
                                                    <?php if ($diasRestantes <0 ):?>
                                                        <span class="badge badge-danger">Vencido (<?=abs($diasRestantes)?> días)</span>
                                                    <?php elseif ($diasRestantes <=30 ):?>
                                                        <span class= "badge badge-warning">Por Vencer(<?=abs($diasRestantes)?> días)</span>
                                                    <?php else:?>
                                                        <span class= "badge badge-success">Vigente(<?=abs($diasRestantes)?> días)</span>
                                                    <?php  endif;?>
                                                </td>-->


                                                <td>
                                                    <?php if (empty($servicio['proximo_servicio'])): ?>
                                                        <span class="text-muted">—</span>
                                                    <?php else: ?>
                                                        <?php
                                                            $hoy=date('Y-m-d');
                                                            $diasRestantes = floor((strtotime($servicio['proximo_servicio'])-strtotime($hoy))/86400);
                                                        ?>
                                                        <?php if ($diasRestantes < 0): ?>
                                                            <span class="badge badge-danger">Vencido (<?= abs($diasRestantes) ?> días)</span>
                                                        <?php elseif ($diasRestantes <= 30): ?>
                                                            <span class="badge badge-warning">Por Vencer (<?= $diasRestantes ?> días)</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">Vigente (<?= $diasRestantes ?> días)</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($servicio['proximo_servicio'])): ?>
                                                        No Aplica
                                                    <?php else: ?>
                                                        <?php
                                                            $hoy=date('Y-m-d');
                                                            $diasRestantes = floor((strtotime($servicio['proximo_servicio'])-strtotime($hoy))/86400);
                                                        ?>
                                                        <?php if ($diasRestantes < 0): ?>
                                                            Vencido
                                                        <?php elseif ($diasRestantes <= 30): ?>
                                                            Por Vencer
                                                        <?php else: ?>
                                                            Vigente
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($servicio['estado_servicio'] ?? '') ?></td>
                                                <td>
                                                    <?php if ($servicio['tipo_registro'] == 'por_asignar' || ($servicio['estado_servicio'] ?? null) === 'material_comprado'):?>
                                                <a href="<?= base_url('servicios_autos/edit/'.$servicio['id'])?>"class="btn btn-success btn-sm">
                                                    Realizar
                                                </a>
                                                <?php else:?>
                                                    <a href="<?=base_url('servicios_autos/edit/'.$servicio['id'])?>" class="btn btn-warning btn-sm">
                                                        Editar
                                                    </a>
                                                    <?php endif;?>
                                                    <a href="<?= base_url ('servicios_autos/detalles/'. $servicio['id'])?>" class=" btn btn-info btn-sm">
                                                        Detalles
                                                    </a>
                                        </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="12" class="text-center">
                                                No existen servicios registrados
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>
 <!-- modal, se movio a la vista de autos 
  
<div class="modal fade" id="modalAuto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" 
            enctype="multipart/form-data"
            action="<?= base_url('autos/store') ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Auto</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Placas</label>
                        <input type="text" name="placas" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Imagenes</label>
                        <input type="file" name="imagenes[]" multiple accept="image/*" class= "form-control-file">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>-->

<!-- MODAL SERVICIO -->
<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="<?= base_url('servicios_autos/store') ?>" enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Auto</label>
                        <select name="auto_id" class="form-control" required>
                            <option value="">Seleccione un auto</option>
                            <?php foreach ($autos as $auto): ?>
                                <option value="<?= $auto['id'] ?>">
                                    <?php if (!empty($auto['alias'])):?>
                                    <?= esc($auto['alias']) ?><?php else:?><!--En caso de que no tenga alias...-->
                                            (Sin Alias)
                                        <?php endif;?> - 
                                    <?= esc($auto['marca']) ?>
                                    <?= esc($auto['modelo']) ?>
                                    (<?= esc($auto['placas']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fecha del Registro y/o Servicio</label>
                        <input type="date" name="fecha_servicio" class="form-control" required>
                    </div>

                    <div class="form-group">

                        <label>Tipo de Registro</label>

                        <select
                            name="tipo_registro"
                            id="tipo_registro"
                            class="form-control"
                            required>

                            <option value="" selected disabled>Seleccione un tipo de registro</option>
                            <option value="unico">Único</option>
                            <option value="periodico">Periódico</option>
                            <option value="por_asignar">Por Asignar</option>

                        </select>

                    </div>

                    <div class="form-group" id="grupoCategoriaServicio">
                        <label>Categoría del Servicio</label>
                        <select name="categoria_servicio" id="categoria_servicio" class="form-control">
                            <option value="">Seleccione una categoría</option>
                            <option value="Mecánico">Mecánico</option>
                            <option value="Eléctrico">Eléctrico</option>
                            <option value="Suspensión">Suspensión</option>
                            <option value="Frenos">Frenos</option>
                            <option value="Llantas">Llantas</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group" id="grupoTipoServicioSelect">
                        <label>Servicio</label>
                        <select name="tipo_servicio_select" id="tipo_servicio_select" class="form-control">
                            <option value="">Seleccione una categoría primero</option>
                        </select>
                    </div>

                    <div class="form-group" id="grupoTipoServicioTexto">
                        <label>Tipo Servicio</label>
                        <input type="text" name="tipo_servicio_texto" id="tipo_servicio_texto" class="form-control">
                    </div>

                    <div class="form-group" id="grupoOtroServicio" style="display:none;">
                        <label>Especifique el servicio</label>
                        <input type="text" name="tipo_servicio_otro" id="tipo_servicio_otro" class="form-control">
                    </div>

                        <div class="form-group" id="grupoDescripcion">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>

                        <div class="form-group" id="grupoEstadoServicio">
                            <label>Estado del Servicio:</label>
                            <select name="estado_servicio" 
                            id="estado_servicio"
                            class="form-control">
                        <option value="" selected disabled>Seleccione un estado</option>
                    <option value="material_comprado">Material Comprado</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="realizado">Realizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                        </div>

                        <div class="form-group" id="grupoObservacionEstado">
                            <label>Observacion del estado</label>
                            <textarea name="observacion_estado" id="observacion_estado" class="form-control" rows="3"
                            placeholder="Ejemplo: se compro el retrovisor derecho, falta colocarlo"></textarea>
                        </div>
                    
                    <div class="form-group" id="grupoProximoServicio">
                        <label id="labelProximoServicio">Próximo Servicio</label>
                        <input type="date" name="proximo_servicio" id="proximo_servicio" class="form-control">
                    </div>

                    <div class="form-group" id="grupoEvidencias">
                        <label>Evidencia(s)</label>
                        <input type="file" name="evidencias[]" multiple accept="image/*" class= "form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoRegistro = document.getElementById('tipo_registro');
    const grupoCategoria = document.getElementById('grupoCategoriaServicio');
    const categoria = document.getElementById('categoria_servicio');

    const grupoTipoSelect = document.getElementById('grupoTipoServicioSelect');
    const tipoServicioSelect = document.getElementById('tipo_servicio_select');

    const grupoTipoTexto = document.getElementById('grupoTipoServicioTexto');
    const tipoServicioTexto = document.getElementById('tipo_servicio_texto');

    const grupoOtro = document.getElementById('grupoOtroServicio');
    const tipoServicioOtro = document.getElementById('tipo_servicio_otro');

    const grupoProximo = document.getElementById('grupoProximoServicio');
    const proximoServicio = document.getElementById('proximo_servicio');
    const grupoDescripcion = document.getElementById('grupoDescripcion');

    const grupoEstadoServicio = document.getElementById('grupoEstadoServicio');
    const grupoObservacionEstado = document.getElementById('grupoObservacionEstado');

    const estadoServicio = document.getElementById('estado_servicio');
    const observacionEstado = document.getElementById('observacion_estado');

    const serviciosPorCategoria = {
        "Mecánico": [
            "Cambio de aceite",
            "Afinación",
            "Cambio de bujías",
            "Cambio de filtros",
            "Otro"
        ],
        "Eléctrico": [
            "Cambio de batería",
            "Cambio de alternador",
            "Cambio de luces",
            "Diagnóstico eléctrico",
            "Otro"
        ],
        "Suspensión": [
            "Cambio de amortiguadores",
            "Cambio de rótulas",
            "Alineación",
            "Balanceo",
            "Otro"
        ],
        "Frenos": [
            "Cambio de balatas",
            "Cambio de discos",
            "Cambio de líquido de frenos",
            "Reparación de frenos",
            "Otro"
        ],
        "Llantas": [
            "Cambio de llantas",
            "Rotación de llantas",
            "Reparación de llanta",
            "Balanceo",
            "Otro"
        ],
        "Otro": [
            "Otro"
        ]
    };

    function limpiarCampos() {
        categoria.value = '';
        tipoServicioSelect.innerHTML = '<option value="">Seleccione una categoría primero</option>';
        tipoServicioTexto.value = '';
        tipoServicioOtro.value = '';
        proximoServicio.value = '';

        categoria.required = false;
        tipoServicioSelect.required = false;
        tipoServicioTexto.required = false;
        tipoServicioOtro.required = false;
        proximoServicio.required = false;
    }

    function cargarServicios() {
        const categoriaSeleccionada = categoria.value;

        tipoServicioSelect.innerHTML = '<option value="">Seleccione un servicio</option>';

        if (!serviciosPorCategoria[categoriaSeleccionada]) return;

        serviciosPorCategoria[categoriaSeleccionada].forEach(function(servicio) {
            const option = document.createElement('option');
            option.value = servicio;
            option.textContent = servicio;
            tipoServicioSelect.appendChild(option);
        });

        grupoOtro.style.display = 'none';
        tipoServicioOtro.required = false;
        tipoServicioOtro.value = '';
    }

    function mostrarOtro() {
        if (tipoServicioSelect.value === 'Otro') {
            grupoOtro.style.display = 'block';
            tipoServicioOtro.required = true;
        } else {
            grupoOtro.style.display = 'none';
            tipoServicioOtro.required = false;
            tipoServicioOtro.value = '';
        }
    }

    function actualizarFormulario() {
        if (!tipoRegistro) return;

        limpiarCampos();

        if (tipoRegistro.value === 'unico') {
            grupoCategoria.style.display = 'none';
            grupoTipoSelect.style.display = 'none';
            grupoOtro.style.display = 'none';
            grupoTipoTexto.style.display = 'block';
            grupoProximo.style.display = 'none';
            grupoDescripcion.style.display = 'block';
            grupoEstadoServicio.style.display = 'block';
            grupoObservacionEstado.style.display = 'block';
            estadoServicio.required = true;

            tipoServicioTexto.required = true;
        }

        if (tipoRegistro.value === 'periodico') {
            grupoCategoria.style.display = 'block';
            grupoTipoSelect.style.display = 'block';
            grupoTipoTexto.style.display = 'none';
            grupoProximo.style.display = 'block';
            grupoDescripcion.style.display = 'none';
            grupoEstadoServicio.style.display = 'block';
            grupoObservacionEstado.style.display = 'block';
            estadoServicio.required = true;

            categoria.required = true;
            tipoServicioSelect.required = true;
            proximoServicio.required = false;
        }

        if (tipoRegistro.value === 'por_asignar') {
            grupoCategoria.style.display = 'none';
            grupoTipoSelect.style.display = 'none';
            grupoOtro.style.display = 'none';
            grupoTipoTexto.style.display = 'block';
            grupoProximo.style.display = 'block';
            grupoDescripcion.style.display = 'none';
            
            grupoEstadoServicio.style.display = 'none';
            grupoObservacionEstado.style.display = 'none';
            estadoServicio.required = false;
            estadoServicio.value = '';
            observacionEstado.value = '';

            tipoServicioTexto.required = true;
            proximoServicio.required = true;
        }
    }

    tipoRegistro.addEventListener('change', actualizarFormulario);
    categoria.addEventListener('change', cargarServicios);
    tipoServicioSelect.addEventListener('change', mostrarOtro);

    actualizarFormulario();
});
</script>



<?= $this->include('layouts/footerListas') ?>

<script>
    $(document).ready(function(){
        var tabla = $('#example1').DataTable();
        tabla.column(9).visible(false);
        tabla.column(10).visible(false);
        $('.filtro-card').on('click', function(){
            var estado = $(this).data('estado');
            tabla.column(10).search('');
            tabla.column(9).search(estado).draw();
        });
        $('.filtro-servicio-card').on('click', function(){
            var estadoServicio = $(this).data('estado-servicio');
            tabla.column(9).search('');
            tabla.column(10).search(estadoServicio).draw();
        });
    })
</script>

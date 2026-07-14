<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<?php
$estadoActual = old('estado_servicio', $servicio['estado_servicio'] ?? '');
$observacionActual = old('observacion_estado', $servicio['observacion_estado'] ?? '');
$categorias = ['Mecánico', 'Eléctrico', 'Suspensión', 'Frenos', 'Llantas', 'Otro'];
?>

<div class="content-wrapper">
<section class="content-header"><div class="container-fluid"><h1>Editar Servicio de Auto</h1></div></section>
<section class="content"><div class="container-fluid"><div class="card">
<div class="card-header"><h3 class="card-title">Datos del Servicio del Auto</h3></div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger m-3"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form method="POST" action="<?= base_url('servicios_autos/update/'.$servicio['id']) ?>" enctype="multipart/form-data">
<div class="card-body">
<?php if($esPendiente):?>
    <div class="alert alert-warning">Este Servicio está Pendiente. Completa los datos para marcarlo como realizado.</div>
    <div class="form-group">
        <label>Tipo de registro</label>
        <select name="tipo_registro" id="tipo_registro_realizar" class="form-control" required>
            <option value="">Seleccione...</option>
            <option value="unico" <?= old('tipo_registro') === 'unico' ? 'selected' : '' ?>>Único</option>
            <option value="periodico" <?= old('tipo_registro') === 'periodico' ? 'selected' : '' ?>>Periódico</option>
        </select>
    </div>
<?php else: ?>
    <input type="hidden" name="tipo_registro" id="tipo_registro_realizar" value="<?= esc($servicio['tipo_registro']) ?>">
<?php endif; ?>

    <div class="form-group">
        <label>Fecha Servicio</label>
        <input type="date" name="fecha_servicio" class="form-control" value="<?= old('fecha_servicio', $esPendiente ? date('Y-m-d') : $servicio['fecha_servicio']) ?>" required>
    </div>

    <div class="form-group" id="grupoCategoriaServicio">
        <label>Categoría del Servicio</label>
        <select name="categoria_servicio" id="categoria_servicio" class="form-control">
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria ?>" <?= old('categoria_servicio', $servicio['categoria_servicio'] ?? '') === $categoria ? 'selected' : '' ?>><?= $categoria ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group" id="grupoTipoServicioSelect">
        <label>Servicio</label>
        <select name="tipo_servicio_select" id="tipo_servicio_select" class="form-control" data-valor-actual="<?= esc(old('tipo_servicio_select', $servicio['tipo_servicio'] ?? '')) ?>">
            <option value="">Seleccione una categoría primero</option>
        </select>
    </div>

    <div class="form-group" id="grupoTipoServicioTexto">
        <label>Tipo Servicio</label>
        <input type="text" name="tipo_servicio" id="tipo_servicio_texto" class="form-control" value="<?= esc(old('tipo_servicio', $servicio['tipo_servicio'])) ?>">
    </div>

    <div class="form-group" id="grupoOtroServicio" style="display:none;">
        <label>Especifique el servicio</label>
        <input type="text" name="tipo_servicio_otro" id="tipo_servicio_otro" class="form-control" value="<?= esc(old('tipo_servicio_otro')) ?>">
    </div>

    <div class="form-group" id="grupoDescripcion">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control" value="<?= esc(old('descripcion', $servicio['descripcion'])) ?>">
    </div>

    <div class="form-group" id="grupoEstadoServicio">
        <label>Estado del Servicio</label>
        <select name="estado_servicio" id="estado_servicio" class="form-control">
            <option value="">Seleccione un estado</option>
            <option value="material_comprado" <?= $estadoActual === 'material_comprado' ? 'selected' : '' ?>>Material Comprado</option>
            <option value="en_proceso" <?= $estadoActual === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="realizado" <?= $estadoActual === 'realizado' ? 'selected' : '' ?>>Realizado</option>
            <option value="cancelado" <?= $estadoActual === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
        </select>
    </div>

    <div class="form-group" id="grupoObservacionEstado">
        <label>Observación del Estado</label>
        <textarea name="observacion_estado" id="observacion_estado" class="form-control" rows="3"><?= esc($observacionActual) ?></textarea>
    </div>

    <div class="form-group" id="grupoProximoRealizar">
        <label>Próximo Servicio</label>
        <input type="date" name="proximo_servicio" id="proximo_servicio_realizar" class="form-control" value="<?= esc(old('proximo_servicio', $servicio['proximo_servicio'])) ?>">
        <small class="text-muted">Llena este campo si crees necesario programar un próximo servicio.</small>
    </div>

    <div class="form-group"><label>Evidencias Actuales (<?= count($evidencias) ?>)</label><div class="d-flex flex-wrap">
        <?php if (!empty($evidencias)): foreach ($evidencias as $evidencia): ?>
            <div class="text-center m-1"><img src="<?= base_url('public/uploads/servicios_autos/'.$evidencia['archivo']) ?>" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover; cursor:pointer;" onclick="verImagen(this)"><br>
            <a href="<?= base_url('servicios_autos/eliminar-evidencia/'. $evidencia['id']) ?>" class="btn btn-danger btn-sm mt-1" onclick="return confirm('¿Desea eliminar esta evidencia?')">Eliminar</a></div>
        <?php endforeach; else: ?><p>Sin evidencias registradas.</p><?php endif; ?>
    </div></div>

    <div class="form-group"><label>Evidencia(s)</label><input type="file" name="evidencias[]" multiple accept="image/*" class="form-control"></div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-<?= $esPendiente ? 'success' : 'primary' ?>"><?= $esPendiente ? 'Realizar Servicio' : 'Guardar Cambios' ?></button>
    <a href="<?= base_url('servicios_autos') ?>" class="btn btn-secondary">Cancelar</a>
</div>
</form>
</div></div></section></div>

<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-body text-center p-2"><img id="imgPreview" src="" class="img-fluid"></div></div></div></div>

<script>
function verImagen(elemento){ document.getElementById('imgPreview').setAttribute('src', elemento.getAttribute('src')); $('#modalImagen').modal('show'); }
document.addEventListener('DOMContentLoaded', function(){
    const tipoRegistro = document.getElementById('tipo_registro_realizar');
    const grupoProximo = document.getElementById('grupoProximoRealizar');
    const proximoServicio = document.getElementById('proximo_servicio_realizar');
    const grupoCategoria = document.getElementById('grupoCategoriaServicio');
    const categoria = document.getElementById('categoria_servicio');
    const grupoTipoSelect = document.getElementById('grupoTipoServicioSelect');
    const tipoServicioSelect = document.getElementById('tipo_servicio_select');
    const grupoTipoTexto = document.getElementById('grupoTipoServicioTexto');
    const tipoServicioTexto = document.getElementById('tipo_servicio_texto');
    const grupoOtro = document.getElementById('grupoOtroServicio');
    const tipoServicioOtro = document.getElementById('tipo_servicio_otro');
    const grupoDescripcion = document.getElementById('grupoDescripcion');
    const estadoServicio = document.getElementById('estado_servicio');
    const actual = tipoServicioSelect.dataset.valorActual;
    const serviciosPorCategoria = {"Mecánico":["Cambio de aceite","Afinación","Cambio de bujías","Cambio de filtros","Otro"],"Eléctrico":["Cambio de batería","Cambio de alternador","Cambio de luces","Diagnóstico eléctrico","Otro"],"Suspensión":["Cambio de amortiguadores","Cambio de rótulas","Alineación","Balanceo","Otro"],"Frenos":["Cambio de balatas","Cambio de discos","Cambio de líquido de frenos","Reparación de frenos","Otro"],"Llantas":["Cambio de llantas","Rotación de llantas","Reparación de llanta","Balanceo","Otro"],"Otro":["Otro"]};
    function cargarServicios(){ tipoServicioSelect.innerHTML='<option value="">Seleccione un servicio</option>'; (serviciosPorCategoria[categoria.value] || []).forEach(function(servicio){ const option=document.createElement('option'); option.value=servicio; option.textContent=servicio; if (servicio === actual) option.selected = true; tipoServicioSelect.appendChild(option); }); mostrarOtro(); }
    function mostrarOtro(){ if (tipoServicioSelect.value === 'Otro') { grupoOtro.style.display='block'; tipoServicioOtro.required=true; } else { grupoOtro.style.display='none'; tipoServicioOtro.required=false; } }
    function actualizar(){ const tipo = tipoRegistro.value; categoria.required=false; tipoServicioSelect.required=false; tipoServicioTexto.required=false; proximoServicio.required=false; estadoServicio.required=(tipo === 'unico' || tipo === 'periodico'); if(tipo === 'unico'){ grupoCategoria.style.display='none'; grupoTipoSelect.style.display='none'; grupoOtro.style.display='none'; grupoTipoTexto.style.display='block'; grupoDescripcion.style.display='block'; grupoProximo.style.display='none'; proximoServicio.value=''; tipoServicioTexto.required=true; } else if(tipo === 'periodico'){ grupoCategoria.style.display='block'; grupoTipoSelect.style.display='block'; grupoTipoTexto.style.display='none'; grupoDescripcion.style.display='block'; grupoProximo.style.display='block'; categoria.required=true; tipoServicioSelect.required=true; } else { grupoCategoria.style.display='none'; grupoTipoSelect.style.display='none'; grupoOtro.style.display='none'; grupoTipoTexto.style.display='none'; grupoDescripcion.style.display='none'; grupoProximo.style.display='none'; estadoServicio.required=false; } }
    categoria.addEventListener('change', cargarServicios); tipoServicioSelect.addEventListener('change', mostrarOtro); if (tipoRegistro.tagName === 'SELECT') tipoRegistro.addEventListener('change', actualizar); cargarServicios(); actualizar();
});
</script>
<?= $this->include('layouts/footerListas') ?>

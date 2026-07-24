<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>
<?php $mostrar = static fn($valor) => trim((string) ($valor ?? '')) !== '' ? esc($valor) : '—'; ?>
<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar inventario</h1
                ></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('agenda') ?>">Inicio</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('inventario') ?>">Inventario</a>
                        </li
                        ><li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<section class="content"><div class="container-fluid">
<?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Datos actuales</h3>
    </div>
    <div class="card-body">
<p>
    <strong>Ubicación actual:</strong> B<?= esc($item['bodega']) ?>-A<?= esc($item['anaquel']) ?>-N<?= esc($item['nivel']) ?></p>
<form id="formEditarInventario" action="<?= base_url('inventario/update/' . $item['id']) ?>" 
method="post" 
enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="row">
<div class="form-group col-md-6">
    <label>Nombre</label>
    <input type="text" 
    name="nombre" 
    class="form-control" 
    required 
    maxlength="150" 
    value="<?= old('nombre', $item['nombre']) ?>">
</div>
<div class="form-group col-md-6">
    <label>Alias</label>
    <input 
    type="text" 
    name="alias" 
    class="form-control" 
    maxlength="150" 
    value="<?= old('alias', $item['alias']) ?>">
</div>
<div class="form-group col-md-6">
    <label>Marca</label>
    <input type="text" 
    name="marca" 
    class="form-control" 
    maxlength="100" 
    value="<?= old('marca', $item['marca']) ?>">
</div>
<div class="form-group col-md-6">
    <label>Modelo</label>
    <input type="text" 
    id="modeloEditar"
    name="modelo" 
    class="form-control" 
    maxlength="100"
    data-modelo-original="<?= esc($item['modelo'] ?? '')?>" 
    value="<?= old('modelo', $item['modelo']) ?>">
</div>

<div class="form-group col-md-6">
    <label>Descripcion</label>
    <textarea
    name="descripcion" 
    class="form-control" 
    rows="3"
    placeholder="Agregue caracteristicas, accesorios o informacion adicional del elemento">
    <?= old('descripcion', $item['descripcion']) ?? '' ?></textarea>
    <small class="form-text text-muted">Campo opcional</small>
</div>


<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-boxes mr-2"></i>
                Control de existencias
            </h3>
        </div>
        <div class="card-body">
<div class="form-group mb-0">
    <label>Cantidad Actual</label>
    <input type="number" 
        name="cantidad" 
        class="form-control" 
        min="0" 
        required
        readonly
        value="<?= old('cantidad', $item['cantidad']) ?>">
    <small class="form-text text-muted">
        Representa la cantidad disponible actualmente dentro del inventario.
    </small>
    <a href="<?= base_url('inventario/movimientos?inventario_id=' . $item['id'])?>"
        class="btn btn-outline-primary mt-3">
        <i class="fas fa-exchange-alt mr-1"></i>
        Registrar Movimiento
    </a>
</div>
</div>
</div>
</div>


<div class="form-group col-md-6">
    <label>Tipo de control</label>
    <select name="tipo_control" id="tipo_control" class="form-control" 
    data-valor-original="<?= esc(old('tipo_control', $item['tipo_control']?? ''))?>" required>
        <option value="" disabled
        <?=empty(old('tipo_control', $item['tipo_control'] ?? null)) ? 'selected' : ''?>>
            Seleccione un tipo
        </option>
        <option value="retornable" 
        <?=(old('tipo_control', $item['tipo_control'] ?? null)) === 'retornable' ? 'selected' : ''?>>
            Retornable
        </option>
        <option value="consumible" 
        <?=(old('tipo_control', $item['tipo_control'] ?? null)) === 'consumible' ? 'selected' : ''?>>
            Consumible
        </option>
    </select>
</div>

<div class="form-group col-md-3">
    <label>Bodega</label>
    <input type="number" 
    name="bodega" 
    class="form-control" 
    min="1" required 
    value="<?= old('bodega', $item['bodega']) ?>">
</div>
<div class="form-group col-md-3">
    <label>Anaquel</label>
    <input type="number" 
    name="anaquel" 
    class="form-control" 
    min="1" required 
    value="<?= old('anaquel', $item['anaquel']) ?>">
</div>
<div class="form-group col-md-3">
    <label>Nivel</label>
    <input type="number" 
    name="nivel" 
    class="form-control" 
    min="1" required 
    value="<?= old('nivel', $item['nivel']) ?>">
</div>
<div class="form-group col-md-12">
    <label>Agregar fotografías nuevas</label>
    <input type="file" name="fotos[]" class="form-control" 
    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" 
    multiple>
</div>
</div>
<button type="submit" class="btn btn-primary">Guardar cambios
</button> 
<a href="<?= base_url('inventario') ?>" class="btn btn-secondary">Regresar</a>
</form>
</div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fotografías actuales</h3>
    </div>
    <div class="card-body">
        <div class="row">
<?php if (! empty($fotos)): foreach ($fotos as $foto): ?>
<div class="col-md-3 text-center mb-3">
    <a href="#" data-toggle="modal" 
    data-target="#foto<?= esc($foto['id']) ?>">
    <img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" 
    class="img-thumbnail" 
    style="width:160px;height:160px;object-fit:cover;" 
    alt="Fotografía">
</a>
<br>
<a href="<?= base_url('inventario/eliminar-foto/' . $foto['id']) ?>" 
class="btn btn-sm btn-danger mt-2"
onclick="return confirm('¿Desea eliminar esta fotografia? Esta accion no se puede deshacer');">Eliminar fotografía</a>
</div>
<div class="modal fade" 
id="foto<?= esc($foto['id']) ?>" 
tabindex="-1">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body text-center">
            <img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" 
            class="img-fluid" 
            alt="Vista previa">
        </div>
    </div>
</div>
</div>
<?php endforeach; else: ?>
    <div class="col-12">
        <p class="text-muted">No hay fotografías registradas</p>
    </div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoControl = document.getElementById('tipo_control');

    if (!tipoControl) {
        return;
    }

    let valorConfirmado = tipoControl.dataset.valorOriginal;

    tipoControl.addEventListener('change', function () {
        const nuevoValor = tipoControl.value;

        // Los registros antiguos sin clasificación pueden seleccionarse por primera vez sin alerta.
        if (valorConfirmado === '') {
            valorConfirmado = nuevoValor;
            return;
        }

        if (nuevoValor === valorConfirmado) {
            return;
        }

        const nombreAnterior = valorConfirmado === 'retornable'
            ? 'Retornable'
            : 'Consumible';

        const nombreNuevo = nuevoValor === 'retornable'
            ? 'Retornable'
            : 'Consumible';

        const confirmarCambio = confirm(
            '¿Desea cambiar el tipo de control de "' +
            nombreAnterior +
            '" a "' +
            nombreNuevo +
            '"?\n\nEste cambio modificará la forma en que el elemento será manejado dentro del inventario.'
        );

        if (confirmarCambio) {
            valorConfirmado = nuevoValor;
        } else {
            tipoControl.value = valorConfirmado;
        }
    });
});
</script>

<div class ="modal fade"
id="modalConfirmarCambioModelo"
tabindex="-1"
role="dialog"
aria-labelledby="tituloCambioModelo"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered"
role="document">
<div class="modal-content">
    <div class="modal-header bg-warning">
        <h5 class="modal-title" id="tituloCambioModelo">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Confirmar cambio de modelo
        </h5>
        <button type="button" class="close"
        data-dismiss="modal">
    <span>&times</span>
</button>
    </div>
    <div class="modal-body">
        <p id="mensajeCambioModelo" class="mb-2"></p>
        <p class="text-muted mb-0">
            El modelo funciona como codigo de identificacion. Verifique el dato antes de guardar.
        </p>
    </div>
    <div class="modal-footer">
        <button type="button"
        class="btn btn-secondary"
        data-dismiss="modal">
        Revisar Modelo
        </button>
        <button type="button"
        class="btn btn-warning"
        id="btnConfirmarCambioModelo">
            Continuar con la actualizacion
        </button>
    </div>
</div>
</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function()
{
    const formulario = document.getElementById('formEditarInventario');
    const campoModelo = document.getElementById('modeloEditar');
    const mensaje = document.getElementById('mensajeCambioModelo');
    const botonConfirmar = document.getElementById('btnConfirmarCambioModelo');

    if(!formulario || !campoModelo || !mensaje || !botonConfirmar){
        return;
    }

    const modelosRegistrados = <?=json_encode($modelosRegistrados ?? [],
    JSON_UNESCAPED_UNICODE
    | JSON_HEX_TAG
    | JSON_HEX_APOS
    | JSON_HEX_AMP
    | JSON_HEX_QUOT
    )?>
    
    const modeloOriginal = campoModelo.dataset.modeloOriginal.trim();
    const modalAdvertencia = $('#modalConfirmarCambioModelo');

    let permitirEnvio = false;
    
    function normalizarModelo(modelo){
        return modelo
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '');
    }
    formulario.addEventListener('submit', function (event)
{
    if(permitirEnvio){
        return;
    }
    const modeloNuevo = campoModelo.value.trim();
    if (modeloNuevo === modeloOriginal){
        return;
    }
    event.preventDefault();
    const modeloNormalizado = normalizarModelo(modeloNuevo);
    const coincidencia = modeloNormalizado === '' ? null : modelosRegistrados.find(function(elemento){
        return normalizarModelo(elemento.modelo ?? '') === modeloNormalizado;
    });

    if (modeloNuevo === ''){
        mensaje.textContent = 'Esta eliminando el modelo "' +
        modeloOriginal +
        '" de este elemento. ¿Desea continuar con la actualizacion?';
    }else if (coincidencia){
        const estado = Number(coincidencia.activo) === 1 ? 'activo' : 'dado de baja';
        mensaje.textContent = 'Esta cambiando el modelo "' +
        modeloOriginal + '" por "' + modeloNuevo +
        '". ¿Desea continuar con la actualizacion?';
    }
    modalAdvertencia.modal('show');
});
botonConfirmar.addEventListener('click', function ()
{
    permitirEnvio = true;
    modalAdvertencia.modal('hide');
    formulario.submit();
});
    });
</script>

<?= $this->include('layouts/footerListas') ?>
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<?php
$mostrar = static fn($valor) => trim((string) ($valor ?? '')) !== '' ? esc($valor) : '—';
$ubicacion = static fn($item) => 'B' . esc($item['bodega']) . '-A' . esc($item['anaquel']) . '-N' . esc($item['nivel']);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Inventario</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Inventario</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventario registrado</h3>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalInventario">
                        <i class="fas fa-plus"></i> Agregar al inventario
                    </button>
                </div>
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th><th>Nombre</th><th>Alias</th><th>Marca</th><th>Modelo</th><th>Cantidad</th><th>Ubicación</th><th>Estado</th><th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventario as $item): ?>
                                <?php $fotos = $item['fotos'] ?? []; ?>
                                <tr>
                                    <td>
                                        <?php if (! empty($fotos)): ?>
                                            <a href="#" data-toggle="modal" data-target="#previewFoto<?= esc($fotos[0]['id']) ?>">
                                                <img src="<?= base_url('public/uploads/inventario/' . $fotos[0]['foto']) ?>" alt="Foto de inventario" class="img-thumbnail" style="width:70px;height:70px;object-fit:cover;">
                                            </a>
                                            <?php if (count($fotos) > 1): ?><span class="badge badge-info">+<?= count($fotos) - 1 ?></span><?php endif; ?>
                                            <div class="modal fade" id="previewFoto<?= esc($fotos[0]['id']) ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-body text-center"><img src="<?= base_url('public/uploads/inventario/' . $fotos[0]['foto']) ?>" class="img-fluid" alt="Vista previa"></div></div></div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="far fa-image"></i> Sin foto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['nombre']) ?></td>
                                    <td><?= $mostrar($item['alias']) ?></td>
                                    <td><?= $mostrar($item['marca']) ?></td>
                                    <td><?= $mostrar($item['modelo']) ?></td>
                                    <td><?= esc($item['cantidad']) ?></td>
                                    <td><?= $ubicacion($item) ?></td>
                                    <td><span class="badge badge-<?= (int) $item['activo'] === 1 ? 'success' : 'secondary' ?>"><?= (int) $item['activo'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                                    <td>
                                        <a href="<?= base_url('inventario/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="<?= base_url('inventario/detalles/' . $item['id']) ?>" class="btn btn-sm btn-info">Detalles</a>
                                        <?php if ((int) $item['activo'] === 1): ?>
                                            <a href="<?= base_url('inventario/baja/' . $item['id']) ?>" class="btn btn-sm btn-secondary">Dar de baja</a>
                                        <?php else: ?>
                                            <a href="<?= base_url('inventario/reactivar/' . $item['id']) ?>" class="btn btn-sm btn-success">Reactivar</a>
                                        <?php endif; ?>
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

<div class="modal fade" id="modalInventario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formRegistroInventario" action="<?= base_url('inventario/store') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header"><h5 class="modal-title">Agregar al inventario</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6"><label>Nombre</label><input type="text" name="nombre" class="form-control" required maxlength="150" value="<?= old('nombre') ?>"></div>
                    <div class="form-group col-md-6"><label>Alias</label><input type="text" name="alias" class="form-control" maxlength="150" value="<?= old('alias') ?>"></div>
                    <div class="form-group col-md-6"><label>Marca</label><input type="text" name="marca" class="form-control" maxlength="100" value="<?= old('marca') ?>"></div>
                    <div class="form-group col-md-6">
                        <label>Modelo</label>
                        <input type="text" 
                        id="modeloRegistro"
                        name="modelo" 
                        class="form-control" 
                        maxlength="100"
                        autocomplete="off"
                        value="<?= old('modelo') ?>">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Descripcion</label>
                        <textarea  name="descripcion" 
                        class="form-control" rows="3" 
                        placeholder="Agregue caracteristicas, accesorios o informacion adicional del elemento">
                         <?=old('descripcion') ?>
                        </textarea>
                        <small class="form-text text-muted">
                            Campo opcional
                        </small>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Cantidad</label>
                        <input type="number" 
                        name="cantidad" 
                        class="form-control" 
                        min="0" required 
                        value="<?= old('cantidad', 1) ?>">
                    </div>
                    <div class="form-group col-md-8">
                        <label>Tipo de control</label>
                        <select name="tipo_control" class="form-control" required>
                            <option value="" selected disabled>Seleccione un tipo</option>
                            <option value="retornable" <?=old('tipo_control') === 'retornable' ? 'selected' : '' ?>>
                                Retornable
                            </option>
                            <option value="consumible" <?=old('tipo_control') === 'consumible' ? 'selected' : '' ?>>
                                Consumible
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            Retornable: Se prestan y deben regresar. <br>
                            Consumible: Se entregan y reducen existencias.
                        </small>
                    </div>
                    <div>
                    <br>
                </div>
                    <div class="form-group col-md-3"><label>Bodega</label><input type="number" name="bodega" class="form-control" min="1" required value="<?= old('bodega') ?>"></div>
                    <div class="form-group col-md-3"><label>Anaquel</label><input type="number" name="anaquel" class="form-control" min="1" required value="<?= old('anaquel') ?>"></div>
                    <div class="form-group col-md-3"><label>Nivel</label><input type="number" name="nivel" class="form-control" min="1" required value="<?= old('nivel') ?>"></div>
                    <div class="form-group col-md-12"><label>Fotografías</label><input type="file" name="fotos[]" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple>
                <small class="form-text text-muted">Formatos Permitidos: JPG, JPGE, PNG Y WEBP. Puede seleccionar varias fotografias.</small></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
        </form>
    </div>
</div>

<div class="modal fade"
    id="modalModeloDuplicado"
    tabindex="-1"
    role="dialog"
    aria-labelledby="tituloModeloDuplicado"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="tituloModeloDuplicado">
                    <i class="fas fa-exclamation-triangle mr-2"> Modelo Posiblemente registrado</i>
                </h5>
                <button type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Cerrar"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="mensajeModeloDuplicado" class="mb-2"></p>
                <p class="text-muted mb-0">
                    Verifique que no se trate del mismo elemento antes de continuar con el registro.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnRevisarModelo" data-dismiss="modal">
                    Revisar Modelo
                </button>
                <button type="button" class="btn btn-warning" id="btnContinuarModelo">
                    Continuar de todos modos
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function()
{
    const formulario = document.getElementById('formRegistroInventario');
    const campoModelo = document.getElementById('modeloRegistro');
    const mensajeDuplicado = document.getElementById('mensajeModeloDuplicado');
    const botonContinuar = document.getElementById('btnContinuarModelo');

    if (! formulario || ! campoModelo || ! mensajeDuplicado || ! botonContinuar){
        return
    }
    //comparar el modelo ingresado con los modelos ya registrados
    const modelosRegistrados = <?= json_encode(
        array_values(
            array_filter(
                array_map(
                    static function ($item){
                        $modelo = trim((string)($item['modelo'] ?? ''));
                        if ($modelo === ''){
                            return null;
                        }
                        return['modelo' => $modelo,
                        'nombre' => $item['nombre'] ?? 'Elemento sin nombre',
                        'activo' => (int)
                        ($item ['activo'] ?? 0),
                        ];
                    },
                    $inventario
                )
            )
        ),
        JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    )?>;
    let permitirEnvio = false;

    const modalRegistro = $('#modalInventario');
    const modalAdvertencia = $('#modalModeloDuplicado');

    function normalizarModelo(modelo){
        return modelo.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]/g, '');
    }
    formulario.addEventListener('submit', function(event){
        if (permitirEnvio){
            return;
        }
        const modeloIngresado = campoModelo.value.trim();
        if (modeloIngresado === ''){
            return;
        }
        const modeloNormalizado = normalizarModelo(modeloIngresado);
        const coincidencia = modelosRegistrados.find(function (elemento){
            return normalizarModelo(elemento.modelo) === modeloNormalizado;
        });
        if (! coincidencia) {
            return;
        }
        event.preventDefault();
        const estado = coincidencia.activo === 1 ? 'actualmente se encuentra activo' : 'actualmente se encuentra dado de baja';

        mensajeDuplicado.textContent = 
        'Ya existe el elemento "' + coincidencia.nombre + '" con el modelo "' + coincidencia.modelo + '" , el cual ' + estado;

        modalRegistro.one('hidden.bs.modal', function()
    {
        modalAdvertencia.modal('show');
    });
    modalRegistro.modal('hide');
    });
    botonContinuar.addEventListener('click', function()
{
    permitirEnvio = true;
    modalAdvertencia.modal('hide');
    modalAdvertencia.one('hidden.bs.modal', function()
    {
    formulario.submit();
    });
});
    modalAdvertencia.on('hidden.bs.modal', function()
    {
        if (! permitirEnvio) {
            modalRegistro.modal('show');
            modalRegistro.one('shown.bs.modal', function (){
                campoModelo.focus();
            });
        }
    });
});
</script>


<?= $this->include('layouts/footerListas') ?>



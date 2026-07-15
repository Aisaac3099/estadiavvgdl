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
        <form action="<?= base_url('inventario/store') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header"><h5 class="modal-title">Agregar al inventario</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6"><label>Nombre</label><input type="text" name="nombre" class="form-control" required maxlength="150" value="<?= old('nombre') ?>"></div>
                    <div class="form-group col-md-6"><label>Alias</label><input type="text" name="alias" class="form-control" maxlength="150" value="<?= old('alias') ?>"></div>
                    <div class="form-group col-md-6"><label>Marca</label><input type="text" name="marca" class="form-control" maxlength="100" value="<?= old('marca') ?>"></div>
                    <div class="form-group col-md-6"><label>Modelo</label><input type="text" name="modelo" class="form-control" maxlength="100" value="<?= old('modelo') ?>"></div>
                    <div class="form-group col-md-3"><label>Cantidad</label><input type="number" name="cantidad" class="form-control" min="0" required value="<?= old('cantidad', 1) ?>"></div>
                    <div class="form-group col-md-3"><label>Bodega</label><input type="number" name="bodega" class="form-control" min="1" required value="<?= old('bodega') ?>"></div>
                    <div class="form-group col-md-3"><label>Anaquel</label><input type="number" name="anaquel" class="form-control" min="1" required value="<?= old('anaquel') ?>"></div>
                    <div class="form-group col-md-3"><label>Nivel</label><input type="number" name="nivel" class="form-control" min="1" required value="<?= old('nivel') ?>"></div>
                    <div class="form-group col-md-12"><label>Fotografías</label><input type="file" name="fotos[]" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
        </form>
    </div>
</div>

<?= $this->include('layouts/footerListas') ?>

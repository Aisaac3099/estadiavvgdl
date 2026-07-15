<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>
<?php $mostrar = static fn($valor) => trim((string) ($valor ?? '')) !== '' ? esc($valor) : '—'; ?>
<div class="content-wrapper">
<section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Editar inventario</h1></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li><li class="breadcrumb-item"><a href="<?= base_url('inventario') ?>">Inventario</a></li><li class="breadcrumb-item active">Editar</li></ol></div></div></div></section>
<section class="content"><div class="container-fluid">
<?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= session()->getFlashdata('success') ?></div><?php endif; ?>
<div class="card"><div class="card-header"><h3 class="card-title">Datos actuales</h3></div><div class="card-body">
<p><strong>Ubicación actual:</strong> B<?= esc($item['bodega']) ?>-A<?= esc($item['anaquel']) ?>-N<?= esc($item['nivel']) ?></p>
<form action="<?= base_url('inventario/update/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="row">
<div class="form-group col-md-6"><label>Nombre</label><input type="text" name="nombre" class="form-control" required maxlength="150" value="<?= old('nombre', $item['nombre']) ?>"></div>
<div class="form-group col-md-6"><label>Alias</label><input type="text" name="alias" class="form-control" maxlength="150" value="<?= old('alias', $item['alias']) ?>"></div>
<div class="form-group col-md-6"><label>Marca</label><input type="text" name="marca" class="form-control" maxlength="100" value="<?= old('marca', $item['marca']) ?>"></div>
<div class="form-group col-md-6"><label>Modelo</label><input type="text" name="modelo" class="form-control" maxlength="100" value="<?= old('modelo', $item['modelo']) ?>"></div>
<div class="form-group col-md-3"><label>Cantidad</label><input type="number" name="cantidad" class="form-control" min="0" required value="<?= old('cantidad', $item['cantidad']) ?>"></div>
<div class="form-group col-md-3"><label>Bodega</label><input type="number" name="bodega" class="form-control" min="1" required value="<?= old('bodega', $item['bodega']) ?>"></div>
<div class="form-group col-md-3"><label>Anaquel</label><input type="number" name="anaquel" class="form-control" min="1" required value="<?= old('anaquel', $item['anaquel']) ?>"></div>
<div class="form-group col-md-3"><label>Nivel</label><input type="number" name="nivel" class="form-control" min="1" required value="<?= old('nivel', $item['nivel']) ?>"></div>
<div class="form-group col-md-12"><label>Agregar fotografías nuevas</label><input type="file" name="fotos[]" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple></div>
</div>
<button type="submit" class="btn btn-primary">Guardar cambios</button> <a href="<?= base_url('inventario') ?>" class="btn btn-secondary">Regresar</a>
</form>
</div></div>
<div class="card"><div class="card-header"><h3 class="card-title">Fotografías actuales</h3></div><div class="card-body"><div class="row">
<?php if (! empty($fotos)): foreach ($fotos as $foto): ?>
<div class="col-md-3 text-center mb-3"><a href="#" data-toggle="modal" data-target="#foto<?= esc($foto['id']) ?>"><img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" class="img-thumbnail" style="width:160px;height:160px;object-fit:cover;" alt="Fotografía"></a><br><a href="<?= base_url('inventario/eliminar-foto/' . $foto['id']) ?>" class="btn btn-sm btn-danger mt-2">Eliminar fotografía</a></div>
<div class="modal fade" id="foto<?= esc($foto['id']) ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-body text-center"><img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" class="img-fluid" alt="Vista previa"></div></div></div></div>
<?php endforeach; else: ?><div class="col-12"><p class="text-muted">No hay fotografías registradas</p></div><?php endif; ?>
</div></div></div>
</div></section></div>
<?= $this->include('layouts/footerListas') ?>

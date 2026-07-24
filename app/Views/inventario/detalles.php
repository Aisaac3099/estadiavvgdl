<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>
<?php $mostrar = static fn($valor) => trim((string) ($valor ?? '')) !== '' ? esc($valor) : '—'; ?>
<div class="content-wrapper">
<section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1>Detalles de inventario</h1></div><div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li><li class="breadcrumb-item"><a href="<?= base_url('inventario') ?>">Inventario</a></li><li class="breadcrumb-item active">Detalles</li></ol></div></div></div></section>
<section class="content"><div class="container-fluid">
<div class="card"><div class="card-header"><h3 class="card-title"><?= esc($item['nombre']) ?></h3></div><div class="card-body">
<table class="table table-bordered">
<tr><th>Nombre</th><td><?= esc($item['nombre']) ?></td></tr>
<tr><th>Alias</th><td><?= $mostrar($item['alias']) ?></td></tr>
<tr><th>Marca</th><td><?= $mostrar($item['marca']) ?></td></tr>
<tr><th>Modelo</th><td><?= $mostrar($item['modelo']) ?></td></tr>
<tr><th>Descripcion</th><td><?=$mostrar($item['descripcion'] ?? null)?></td></tr>
<tr><th>Cantidad</th><td><?= esc($item['cantidad']) ?></td></tr>

<tr><th>Tipo de control</th>
<td>
    <?php if (($item['tipo_control'] ?? '') === 'retornable'):?>
        <span class="badge badge-info">Retornable</span>
        <?php elseif (($item['tipo_control'] ?? '') === 'consumible'):?>
            <span class="badge badge-warning">Consumible</span>
            <?php else:?>
                <span class="text-muted">Sin clasificar</span>
                <?php endif;?>
</td></tr>

<tr><th>Ubicación</th><td>B<?= esc($item['bodega']) ?>-A<?= esc($item['anaquel']) ?>-N<?= esc($item['nivel']) ?></td></tr>
<tr><th>Estado</th><td><span class="badge badge-<?= (int) $item['activo'] === 1 ? 'success' : 'secondary' ?>"><?= (int) $item['activo'] === 1 ? 'Activo' : 'Inactivo' ?></span></td></tr>
<tr><th>Fecha de registro</th><td><?= $mostrar($item['created_at']) ?></td></tr>
<tr><th>Última actualización</th><td><?= $mostrar($item['updated_at']) ?></td></tr>
</table>
<a href="<?= base_url('inventario') ?>" class="btn btn-secondary">Regresar al inventario</a>
</div></div>
<div class="card"><div class="card-header"><h3 class="card-title">Fotografías</h3></div><div class="card-body"><div class="row">
<?php if (! empty($fotos)): foreach ($fotos as $foto): ?>
<div class="col-md-3 text-center mb-3"><a href="#" data-toggle="modal" data-target="#foto<?= esc($foto['id']) ?>"><img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" class="img-thumbnail" style="width:160px;height:160px;object-fit:cover;" alt="Fotografía"></a></div>
<div class="modal fade" id="foto<?= esc($foto['id']) ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-body text-center"><img src="<?= base_url('public/uploads/inventario/' . $foto['foto']) ?>" class="img-fluid" alt="Vista previa"></div></div></div></div>
<?php endforeach; else: ?><div class="col-12"><p class="text-muted">No hay fotografías registradas</p></div><?php endif; ?>
</div></div></div>
</div></section></div>
<?= $this->include('layouts/footerListas') ?>

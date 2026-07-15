<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<?php
$estadoLabels = [
    'material_comprado' => ['Material comprado', 'badge-info'],
    'en_proceso' => ['En proceso', 'badge-warning'],
    'realizado' => ['Realizado', 'badge-success'],
    'cancelado' => ['Cancelado', 'badge-dark'],
];
$estadoProximo = null;
$badgeProximo = null;
$diasRestantes = null;
if (!empty($servicio['proximo_servicio'])) {
    $diasRestantes = floor((strtotime($servicio['proximo_servicio']) - strtotime(date('Y-m-d'))) / 86400);
    if ($diasRestantes < 0) {
        $estadoProximo = 'Vencido';
        $badgeProximo = 'badge-danger';
    } elseif ($diasRestantes <= 30) {
        $estadoProximo = 'Por Vencer';
        $badgeProximo = 'badge-warning';
    } else {
        $estadoProximo = 'Vigente';
        $badgeProximo = 'badge-success';
    }
}
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><h1>Detalles del Servicio</h1></div></section>
    <section class="content"><div class="container-fluid">
        <div class="card"><div class="card-header"><h3 class="card-title">Información del Servicio</h3></div><div class="card-body">
            <p><strong>Auto:</strong> <?= esc($auto['alias']) ?> - <?= esc($auto['marca']) ?> <?= esc($auto['modelo']) ?> (<?= esc($auto['placas']) ?>)</p>
            <p><strong>Tipo Registro:</strong>
                <?php if ($servicio['tipo_registro'] == 'unico'): ?><span class="badge badge-success">Único</span>
                <?php elseif ($servicio['tipo_registro'] == 'periodico'): ?><span class="badge badge-primary">Periódico</span>
                <?php else: ?><span class="badge badge-warning">Por Asignar</span><?php endif; ?>
            </p>
            <p><strong>Categoría:</strong> <?= !empty($servicio['categoria_servicio']) ? esc($servicio['categoria_servicio']) : 'No aplica' ?></p>
            <p><strong>Tipo Servicio:</strong> <?= esc($servicio['tipo_servicio']) ?></p>
            <p><strong>Descripción:</strong> <?= !empty($servicio['descripcion']) ? esc($servicio['descripcion']) : '-' ?></p>
            <p><strong>Fecha Servicio:</strong> <?= !empty($servicio['fecha_servicio']) ? date('d/m/Y', strtotime($servicio['fecha_servicio'])) : '-' ?></p>
            <p><strong>Estado del Servicio:</strong>
                <?php if ($servicio['tipo_registro'] == 'por_asignar'): ?>
                    <span class="badge badge-warning">Por asignar</span>
                <?php elseif (!empty($servicio['estado_servicio']) && isset($estadoLabels[$servicio['estado_servicio']])): ?>
                    <span class="badge <?= $estadoLabels[$servicio['estado_servicio']][1] ?>"><?= $estadoLabels[$servicio['estado_servicio']][0] ?></span>
                <?php else: ?>
                    <span class="badge badge-secondary">Sin estado registrado</span>
                <?php endif; ?>
            </p>
            <p><strong>Observación del Estado:</strong> <?= !empty($servicio['observacion_estado']) ? esc($servicio['observacion_estado']) : 'Sin observación' ?></p>
            <p><strong>Próximo Servicio:</strong>
                <?php if (!empty($servicio['proximo_servicio'])): ?><?= date('d/m/Y', strtotime($servicio['proximo_servicio'])) ?><?php else: ?><span class="badge badge-secondary">No aplica</span><?php endif; ?>
            </p>
            <p><strong>Estado Próximo Servicio:</strong>
                <?php if ($diasRestantes === null): ?>
                    <span class="text-muted">—</span>
                <?php else: ?>
                    <span class="badge <?= $badgeProximo ?>"><?= $estadoProximo ?> (<?= abs($diasRestantes) ?> días)</span>
                <?php endif; ?>
            </p>
        </div></div>
        <div class="card"><div class="card-header"><h3 class="card-title">Evidencias</h3></div><div class="card-body">
            <?php if (!empty($evidencias)): ?><div class="d-flex flex-wrap"><?php foreach ($evidencias as $evidencia): ?>
                <img src="<?= base_url('public/uploads/servicios_autos/'.$evidencia['archivo']) ?>" class="img-thumbnail m-1" style="width:120px; height:120px; object-fit:cover; cursor:pointer;" onclick="verImagen(this)">
            <?php endforeach; ?></div><?php else: ?><p>Sin evidencias registradas.</p><?php endif; ?>
        </div></div>
        <a href="<?= base_url('servicios_autos') ?>" class="btn btn-secondary">Regresar</a>
    </div></section>
</div>
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-body text-center p-2"><img id="imgPreview" src="" class="img-fluid"></div></div></div></div>
<script>function verImagen(elemento) { document.getElementById('imgPreview').setAttribute('src', elemento.getAttribute('src')); $('#modalImagen').modal('show'); }</script>
<?= $this->include('layouts/footerListas') ?>

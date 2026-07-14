<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Detalles del Servicio</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Servicio</h3>
                </div>

                <div class="card-body">

                    <p><strong>Auto:</strong> <?= esc($auto['alias']) ?></p>

                    <p>
                        <strong>Tipo Registro:</strong>

                        <?php if ($servicio['tipo_registro'] == 'unico'): ?>
                            <span class="badge badge-success">Único</span>
                        <?php elseif ($servicio['tipo_registro'] == 'periodico'): ?>
                            <span class="badge badge-primary">Periódico</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Por Asignar</span>
                        <?php endif; ?>
                    </p>

                    <p>
                        <strong>Categoría:</strong>
                        <?= !empty($servicio['categoria_servicio'])
                            ? esc($servicio['categoria_servicio'])
                            : 'No aplica' ?>
                    </p>

                    <p><strong>Tipo Servicio:</strong> <?= esc($servicio['tipo_servicio']) ?></p>

                    <p>
                        <strong>Descripción:</strong>
                        <?= !empty($servicio['descripcion'])
                            ? esc($servicio['descripcion'])
                            : '-' ?>
                    </p>

                    <p><strong>Fecha Servicio:</strong> <?= esc($servicio['fecha_servicio']) ?></p>

                    <p>
                        <strong>Próximo Servicio:</strong>
                        <?= !empty($servicio['proximo_servicio'])
                            ? esc($servicio['proximo_servicio'])
                            : 'No aplica' ?>
                    </p>

                    <p>
                        <strong>Estado:</strong>

                        <?php if ($servicio['tipo_registro'] == 'por_asignar'): ?>

                                <?php
                                    $hoy = date('Y-m-d');
                                    $diasRestantes = floor((strtotime($servicio['proximo_servicio']) - strtotime($hoy)) / 86400);
                                ?>

                                <?php if ($diasRestantes < 0): ?>

                                    <span class="badge badge-danger">
                                        Vencido (<?= abs($diasRestantes) ?> días)
                                    </span>

                                <?php elseif ($diasRestantes <= 30): ?>

                                    <span class="badge badge-warning">
                                        Por Vencer (<?= $diasRestantes ?> días)
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-success">
                                        Vigente (<?= $diasRestantes ?> días)
                                    </span>

                                <?php endif; ?>

                            <?php elseif (empty($servicio['proximo_servicio'])): ?>

                                <span class="badge badge-secondary">No Aplica</span>

                            <?php else: ?>

                                <?php
                                    $hoy = date('Y-m-d');
                                    $diasRestantes = floor((strtotime($servicio['proximo_servicio']) - strtotime($hoy)) / 86400);
                                ?>

                                <?php if ($diasRestantes < 0): ?>

                                    <span class="badge badge-danger">
                                        Vencido (<?= abs($diasRestantes) ?> días)
                                    </span>

                                <?php elseif ($diasRestantes <= 30): ?>

                                    <span class="badge badge-warning">
                                        Por Vencer (<?= $diasRestantes ?> días)
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-success">
                                        Vigente (<?= $diasRestantes ?> días)
                                    </span>

                                <?php endif; ?>

                            <?php
                                $hoy = date('Y-m-d');
                                $diasRestantes = floor((strtotime($servicio['proximo_servicio']) - strtotime($hoy)) / 86400);
                            ?>

                            <?php if ($diasRestantes < 0): ?>
                                <span class="badge badge-danger">Vencido (<?= abs($diasRestantes) ?> días)</span>
                            <?php elseif ($diasRestantes <= 30): ?>
                                <span class="badge badge-warning">Próximo (<?= $diasRestantes ?> días)</span>
                            <?php else: ?>
                                <span class="badge badge-success">Vigente (<?= $diasRestantes ?> días)</span>
                            <?php endif; ?>

                        <?php endif; ?>
                    </p>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Evidencias</h3>
                </div>

                <div class="card-body">

                    <?php if (!empty($evidencias)): ?>

                        <div class="d-flex flex-wrap">

                            <?php foreach ($evidencias as $evidencia): ?>

                                <img
                                    src="<?= base_url('public/uploads/servicios_autos/'.$evidencia['archivo']) ?>"
                                    class="img-thumbnail m-1"
                                    style="width:120px; height:120px; object-fit:cover; cursor:pointer;"
                                    onclick="verImagen(this)">

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <p>Sin evidencias registradas.</p>

                    <?php endif; ?>

                </div>
            </div>

            <a href="<?= base_url('servicios_autos') ?>" class="btn btn-secondary">
                Regresar
            </a>

        </div>
    </section>

</div>

<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-2">
                <img id="imgPreview" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function verImagen(elemento) {
    let src = elemento.getAttribute('src');
    document.getElementById('imgPreview').setAttribute('src', src);
    $('#modalImagen').modal('show');
}
</script>

<?= $this->include('layouts/footerListas') ?>
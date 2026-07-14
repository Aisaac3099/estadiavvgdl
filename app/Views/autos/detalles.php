<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?> 

<div class= "content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Detalles del Auto</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php if (!empty($auto['alias'])):?>
                                    <?= esc($auto['alias']) ?><?php else:?><!--En caso de que no tenga alias...-->
                                            (Sin Alias)
                                        <?php endif;?>
                    </h3>
                </div>
                <div class="card-body">
                    <p><strong>Placas:</strong><?= esc($auto['placas'])?></p>
                    <p><strong>Marca:</strong><?= esc($auto['marca'])?></p>
                    <p><strong>Modelo:</strong><?= esc($auto['modelo'])?></p>
                    <p><strong>Estado:</strong><?php if($auto['activo'] == 1):?><span class="badge badge-success">Activo</span>
                <?php else:?> <span class="badge badge-danger">Inactivo</span> <?php endif;?>
                </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Imagenes</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($imagenes)):?>
                        <div class="d-flex flex-wrap">
                            <?php foreach ($imagenes as $imagen):?>
                                <img src="<?=base_url('public/uploads/autos/'.$imagen['imagen'])?>" class="img-auto img-thumbnail m-1" style="width:120px; height:120px; object-fit:cover; cursor:pointer;" onclick="verImagen(this)">
                                <?php endforeach;?>
                        </div>
                        <?php else:?>
                            <p>Sin Imagenes Registradas.</p>
                            <?php endif;?>
                </div>
            </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Servicios Pendientes</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo Servicio</th>
                                        <th>Fecha Programada</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!empty($serviciosPendientes)): ?>

                                        <?php foreach ($serviciosPendientes as $pendiente): ?>

                                            <tr>
                                                <td><?= esc($pendiente['tipo_servicio']) ?></td>

                                                <td>
                                                    <?= !empty($pendiente['proximo_servicio'])
                                                        ? esc($pendiente['proximo_servicio'])
                                                        : 'Sin fecha' ?>
                                                </td>

                                                <td>
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </td>

                                                <td>
                                                    <a href="<?= base_url('servicios_autos/edit/'.$pendiente['id']) ?>"
                                                    class="btn btn-success btn-sm">
                                                        Realizar
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Este auto no tiene servicios pendientes.
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historial de Servicios</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tipo Registro</th>
                                <th>Categoria</th>
                                <th>Tipo Servicio</th>
                                <th>Descripción</th>
                                <th>Fecha Servicio</th>
                                <th>Próximo Servicio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (!empty($historialServicios)): ?>

                            <?php foreach ($historialServicios as $servicio): ?>

                                <tr>
                                    <td>
                                        <?php if ($servicio['tipo_registro'] == 'unico'): ?>
                                            <span class="badge badge-success">Único</span>
                                        <?php elseif ($servicio['tipo_registro'] == 'periodico'): ?>
                                            <span class="badge badge-primary">Periódico</span>
                                        <?php elseif ($servicio['tipo_registro'] == 'por_asignar'): ?>
                                            <span class="badge badge-warning">Por Asignar</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= !empty($servicio['categoria_servicio'])
                                            ? esc($servicio['categoria_servicio'])
                                            : 'No aplica' ?>
                                    </td>

                                    <td><?= esc($servicio['tipo_servicio']) ?></td>

                                    <td>
                                        <?= !empty($servicio['descripcion'])
                                            ? esc($servicio['descripcion'])
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?= !empty($servicio['fecha_servicio'])
                                            ? esc($servicio['fecha_servicio'])
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?= !empty($servicio['proximo_servicio'])
                                            ? esc($servicio['proximo_servicio'])
                                            : 'No aplica' ?>
                                    </td>

                                    <td>
                                        <?php if ($servicio['tipo_registro'] == 'por_asignar'): ?>

                                            <span class="badge badge-warning">Pendiente</span>

                                        <?php elseif (empty($servicio['proximo_servicio'])): ?>

                                            <span class="badge badge-secondary">No aplica</span>

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
                                                    Próximo (<?= $diasRestantes ?> días)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-success">
                                                    Vigente (<?= $diasRestantes ?> días)
                                                </span>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Este auto no tiene servicios registrados.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                    </table>
                    <br>
                    <a href="<?= base_url('autos') ?>" class="btn btn-secondary">
                        Regresar
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog">
    <div class = "modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-2">
                <img id="imgPreview" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function verImagen(elemento){
        let src = elemento.getAttribute('src');
        document.getElementById('imgPreview').setAttribute('src', src);
        $('#modalImagen').modal('show');
    }
</script>




<?= $this->include('layouts/footerListas') ?>
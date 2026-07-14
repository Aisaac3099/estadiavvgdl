<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Servicios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Servicios</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- PRIMERA TABLA -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Servicios - Tabla 1</h3>
                        </div>
                        <div class="card-body">
                            <table id="serviciosTable1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Estatus</th>
                                        <th>Registrado Por</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($servicios as $servicio): ?>
                                    <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>" style="cursor: pointer;">
                                        <td><?= esc($servicio['nombre_cliente']) ?></td>
                                        <td><?= esc($servicio['servicio_descripcion']) ?></td> 
                                        <td><span class="badge <?= esc($servicio['etiqueta']) ?>"><?= esc($servicio['estatus_servicio']) ?></span></td>
                                        <td><?= esc($servicio['registrante'] ?? 'N/A') ?></td>
                                        <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                    </tr> 
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SEGUNDA TABLA -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Servicios - Tabla 2</h3>
                        </div>
                        <div class="card-body">
                            <table id="serviciosTable2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Estatus</th>
                                        <th>Registrado Por</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($servicios as $servicio): ?>
                                    <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>" style="cursor: pointer;">
                                        <td><?= esc($servicio['nombre_cliente']) ?></td>
                                        <td><?= esc($servicio['servicio_descripcion']) ?></td> 
                                        <td><span class="badge <?= esc($servicio['etiqueta']) ?>"><?= esc($servicio['estatus_servicio']) ?></span></td>
                                        <td><?= esc($servicio['registrante'] ?? 'N/A') ?></td>
                                        <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                    </tr> 
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTÓN NUEVO SERVICIO -->
            <div class="row">
                <div class="col-12 text-center mt-3">
                    <a href="<?= base_url('servicioForm') ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-plus"></i> Nuevo Servicio
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript para Dos DataTables -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Primera DataTable
    $('#serviciosTable1').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 10, // 10 registros en la primera tabla
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "order": [[4, "desc"]], // Ordenar por fecha descendente
        "info": true,
        "searching": true
    });

    // Inicializar Segunda DataTable
    $('#serviciosTable2').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 10, // 10 registros en la segunda tabla
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "order": [[4, "desc"]], // Ordenar por fecha descendente
        "info": true,
        "searching": true
    });

    // Evento click para filas en AMBAS tablas
    $(document).on('click', '.clickable-row', function() {
        window.location.href = $(this).data('href');
    });

    // Efectos hover para ambas tablas
    $(document).on('mouseenter', '.clickable-row', function() {
        $(this).css('background-color', '#f8f9fa');
    }).on('mouseleave', '.clickable-row', function() {
        $(this).css('background-color', '');
    });
});
</script>

<style>
.clickable-row:hover {
    background-color: #f8f9fa !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Estilo para ambas tablas */
#serviciosTable1 tbody tr,
#serviciosTable2 tbody tr {
    cursor: pointer;
    transition: all 0.2s ease;
}

#serviciosTable1 tbody tr:hover,
#serviciosTable2 tbody tr:hover {
    transform: scale(1.01);
}

/* Asegurar que se vean bien en móviles */
@media (max-width: 768px) {
    .col-lg-6 {
        margin-bottom: 20px;
    }
}

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.6em;
}
</style>

<?= $this->include('layouts/footerListas') ?>
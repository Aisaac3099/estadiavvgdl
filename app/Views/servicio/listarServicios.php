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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Gestión de Servicios</h3>
                            <a href="<?= base_url('servicioForm') ?>" class="float-sm-right btn btn-success">
                                <i class="fas fa-plus"></i> Nuevo Servicio
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs" id="serviciosTabs" role="tablist">
                                <!-- Nuevo Tab para TODOS -->
                                <li class="nav-item">
                                    <a class="nav-link" id="todos-tab" data-toggle="tab" href="#todos" role="tab">
                                        <span class="badge bg-dark">TODOS</span>
                                        <span class="badge bg-light text-dark">
                                            <?= count($serviciosPorAgendar ?? []) + 
                                                count($serviciosAgendados ?? []) + 
                                                count($serviciosEnProceso ?? []) + 
                                                count($serviciosTerminados ?? []) + 
                                                count($serviciosDetenidos ?? []) + 
                                                count($serviciosCancelados ?? []) ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="por-agendar-tab" data-toggle="tab" href="#por-agendar" role="tab">
                                        <span class="badge bg-danger">POR AGENDAR</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosPorAgendar ?? []) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="agendado-tab" data-toggle="tab" href="#agendado" role="tab">
                                        <span class="badge bg-primary">AGENDADO</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosAgendados ?? []) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="en-proceso-tab" data-toggle="tab" href="#en-proceso" role="tab">
                                        <span class="badge bg-info">EN PROCESO</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosEnProceso ?? []) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="terminado-tab" data-toggle="tab" href="#terminado" role="tab">
                                        <span class="badge bg-success">TERMINADO</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosTerminados ?? []) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="detenido-tab" data-toggle="tab" href="#detenido" role="tab">
                                        <span class="badge bg-warning">DETENIDO</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosDetenidos ?? []) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="cancelado-tab" data-toggle="tab" href="#cancelado" role="tab">
                                        <span class="badge bg-secondary">CANCELADO</span>
                                        <span class="badge bg-light text-dark"><?= count($serviciosCancelados ?? []) ?></span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content" id="serviciosTabsContent">
                                <!-- Nuevo Tab TODOS -->
                                <div class="tab-pane fade" id="todos" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Estatus</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                // Combinar todos los servicios en un solo array
                                                $todosServicios = array_merge(
                                                    $serviciosPorAgendar ?? [],
                                                    $serviciosAgendados ?? [],
                                                    $serviciosEnProceso ?? [],
                                                    $serviciosTerminados ?? [],
                                                    $serviciosDetenidos ?? [],
                                                    $serviciosCancelados ?? []
                                                );
                                                ?>
                                                
                                                <?php foreach ($todosServicios as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td>
                                                        <?php 
                                                        // Determinar el badge según el estatus
                                                        $estatus = $servicio['estatus'] ?? '';
                                                        $badgeClass = '';
                                                        switch($estatus) {
                                                            case 'por_agendar':
                                                                $badgeClass = 'bg-danger';
                                                                $estatusTexto = 'POR AGENDAR';
                                                                break;
                                                            case 'agendado':
                                                                $badgeClass = 'bg-primary';
                                                                $estatusTexto = 'AGENDADO';
                                                                break;
                                                            case 'en_proceso':
                                                                $badgeClass = 'bg-info';
                                                                $estatusTexto = 'EN PROCESO';
                                                                break;
                                                            case 'terminado':
                                                                $badgeClass = 'bg-success';
                                                                $estatusTexto = 'TERMINADO';
                                                                break;
                                                            case 'detenido':
                                                                $badgeClass = 'bg-warning';
                                                                $estatusTexto = 'DETENIDO';
                                                                break;
                                                            case 'cancelado':
                                                                $badgeClass = 'bg-secondary';
                                                                $estatusTexto = 'CANCELADO';
                                                                break;
                                                            default:
                                                                $badgeClass = 'bg-light text-dark';
                                                                $estatusTexto = $estatus;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $badgeClass ?>"><?= $estatusTexto ?></span>
                                                    </td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($todosServicios)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        No hay servicios registrados
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab POR AGENDAR -->
                                <div class="tab-pane fade show active" id="por-agendar" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosPorAgendar as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosPorAgendar)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios por agendar
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Los demás tabs permanecen igual -->
                                <!-- Tab AGENDADO -->
                                <div class="tab-pane fade" id="agendado" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosAgendados as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosAgendados)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios agendados
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab EN PROCESO -->
                                <div class="tab-pane fade" id="en-proceso" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosEnProceso as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosEnProceso)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios en proceso
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab TERMINADO -->
                                <div class="tab-pane fade" id="terminado" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosTerminados as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosTerminados)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios terminados
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab DETENIDO -->
                                <div class="tab-pane fade" id="detenido" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosDetenidos as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosDetenidos)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios detenidos
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab CANCELADO -->
                                <div class="tab-pane fade" id="cancelado" role="tabpanel">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosCancelados as $servicio): ?>
                                                <tr class="clickable-row" data-href="<?= base_url('editServicio/' . $servicio['id']) ?>">
                                                    <td><?= esc($servicio['nombre_cliente']) ?></td>
                                                    <td><?= esc($servicio['servicio']) ?></td>
                                                    <td><?= esc($servicio['fechaRegistrante'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($serviciosCancelados)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No hay servicios cancelados
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript para filas clickeables -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hacer las filas clickeables
    const clickableRows = document.querySelectorAll('.clickable-row');
    
    clickableRows.forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
        
        // Efecto hover
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Activar DataTables en cada tabla si es necesario
    $('.table').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "paging": true,
        "info": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });
});
</script>

<style>
.clickable-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.clickable-row:hover {
    background-color: #f8f9fa !important;
}

.nav-tabs .nav-link {
    border: none;
    padding: 12px 20px;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
    border-bottom: 3px solid #007bff;
}

.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 0;
}

.table-responsive {
    border-radius: 0 0 4px 4px;
}
</style>

<?= $this->include('layouts/footerListas') ?>
<?= $this->include('layouts/headerAdvancedForm') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Agregar Agenda</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Formulario Clientes</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <form action="<?= base_url('/createAgenda') ?>" method="post">
    
    <!-- ✅ CORRECCIÓN: Campo oculto con el nombre correcto -->
    <?php if (isset($servicio)): ?>
        <input type="hidden" name="servicio_id" value="<?= $servicio['servicio_id'] ?>">
        <!-- ✅ NUEVO: Campo oculto para el servicio (requerido por la tabla agenda) -->
        <input type="hidden" name="servicio" value="<?= $servicio['servicio_id'] ?>">
    <?php endif; ?>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row"> 
            <div class="col-md-4">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Agenda</h3> 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Cliente</label> 
                    <select name="cliente" id="cliente_select" class="form-control select2">
                        <option value="">Selecciona...</option>   
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= esc($cliente['id']) ?>"
                                <?= (isset($servicio) && $cliente['id'] == $servicio['cliente_id']) ? 'selected' : '' ?>>
                                <?= esc($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- ✅ CORRECCIÓN: Campo oculto para cliente cuando viene precargado -->
                    <?php if (isset($servicio)): ?>
                        <input type="hidden" name="cliente" value="<?= $servicio['cliente_id'] ?>">
                    <?php endif; ?>
                  </div>   
                </div>   
                <div class="card-body">
                  <div class="form-group">
                    <label>Técnico(s)</label><br>
                    <?php foreach ($tecnicos as $tecnico): ?>
                        <div class="form-check form-check-inline">
                            <input 
                                type="checkbox" 
                                name="tecnicos[]" 
                                value="<?= esc($tecnico['id']) ?>" 
                                id="tec_<?= esc($tecnico['id']) ?>" 
                                class="form-check-input"
                            >
                            <label class="form-check-label" for="tec_<?= esc($tecnico['id']) ?>">
                                <?= esc($tecnico['nombre']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                  </div>
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">fechaInicio</label>
                    <input type="datetime-local" id="inputNombe" name="fechaInicio" class="form-control" onkeydown="return event.key !== 'Enter';" required>
                  </div>   
                </div> 
              </div>
            </div>  
            <div class="col-md-4">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Tiempos</h3> 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Servicios</label> 
                    <?php if (isset($servicio)): ?>
                        <!-- ✅ CORRECCIÓN: Mostrar servicio precargado como texto + campo oculto -->
                        <input type="text" class="form-control" value="<?= $servicio['servicio_descripcion'] ?>" readonly>
                        <small class="text-muted">Servicio precargado desde el sistema</small>
                    <?php else: ?>
                        <select name="servicio" class="form-control select2" id="servicio_select">
                            <option value="">Selecciona...</option>
                        </select>
                    <?php endif; ?>
                  </div>   
                </div>   
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Comentarios</label>  
                    <textarea id="inputComentarios" class="form-control" rows="4" name="comentarios"></textarea>
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="prioridad">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-control" required>
                      <option value="">Selecciona...</option>
                      <option value="Alta" <?= (isset($servicio) && $servicio['prioridad'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                      <option value="Media" <?= (isset($servicio) && $servicio['prioridad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                      <option value="Baja" <?= (isset($servicio) && $servicio['prioridad'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
                    </select>
                  </div>
                </div> 
              </div>
            </div>  
          </div> 
          <div class="row">
            <div class="col-12">
              <a href="<?= base_url('agenda') ?>" class="btn btn-secondary">Cancel</a>
              <input type="submit" value="<?= isset($servicio) ? 'Agendar Servicio' : 'Crear Agenda' ?>" class="btn btn-success float-right">
            </div>
          </div>
        </div>
      </section>
    </form> 
  </div>

<script> 
document.addEventListener('DOMContentLoaded', function () {
    const clienteSelect = document.getElementById('cliente_select');
    const servicioSelect = document.getElementById('servicio_select');

    <?php if (isset($servicio)): ?>
        // Si viene de un servicio, deshabilitar la selección de cliente y servicio
        $(clienteSelect).prop('disabled', true);
        // No necesitamos deshabilitar servicioSelect porque lo reemplazamos por un input
    <?php else: ?>
        // Solo habilitar el cambio dinámico si NO viene de un servicio
        $(clienteSelect).select2();
        $(clienteSelect).on('select2:select', function (e) {
            const clienteId = e.params.data.id;
            
            $(servicioSelect).empty().append('<option value="">Cargando servicios...</option>');

            if (clienteId) {
                fetch(`<?= site_url('getServiciosPorCliente') ?>/${clienteId}`)
                    .then(response => response.json()) 
                    .then(servicios => {
                        $(servicioSelect).empty().append('<option value="">Selecciona un servicio</option>');
                        servicios.forEach(servicio => {
                            const option = new Option(servicio.servicio, servicio.id);
                            $(servicioSelect).append(option);
                        });
                        $(servicioSelect).trigger('change');
                    })
                    .catch(error => {
                        console.error('Error al obtener los servicios:', error);
                        $(servicioSelect).empty().append('<option value="">Error al cargar</option>');
                    });
            } else {
                $(servicioSelect).empty().append('<option value="">Selecciona un servicio</option>');
            }
        });
    <?php endif; ?>
});
</script>

<?= $this->include('layouts/footerAdvancedForm') ?>
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
    <form action="<?= base_url('/agendaForm') ?>" method="post"> 
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
                    <select name="cliente" id="cliente_select"  class="form-control select2">
                        <option value="">Selecciona...</option>   
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= esc($cliente['id']) ?>">
                                <?= esc($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                  </div>   
                </div>   
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">tecnico</label> 

                      <select multiple class="form-control" name="tecnicos[]" id="tecnico"  class="form-control">
                          <<?php foreach ($tecnicos as $tecnico): ?>
                            <option value="<?= esc($tecnico['id']) ?>">
                                <?= esc($tecnico['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                      </select> 

                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">fechaInicio</label>
                    <input type="datetime-local" id="inputNombe" name="fechaInicio" class="form-control" >
                  </div>   
                </div> 
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
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
                    
                     <select name="servicio" class="form-control select2" id="servicio_select">
                        <option value="">Selecciona...</option>   
                        
                    </select> 
                         
                  </div>   
                </div>   
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Comentarios</label>  
                    <textarea id="inputComentarios" class="form-control" rows="4" name="comentarios"></textarea>
                  </div>   
                </div>  
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>  
          </div> 
          <div class="row">
            <div class="col-12">
              <a href="<?= base_url('agenda') ?>" class="btn btn-secondary">Cancel</a>
              <input type="submit" value="Actualizar" class="btn btn-success float-right">
            </div>
          </div>
        </div>
      </section>
      <!-- /.content --> 
    </form> 
  </div>
  <!-- /.content-wrapper --> 


<script> 
document.addEventListener('DOMContentLoaded', function () {

    const clienteSelect = document.getElementById('cliente_select');
    const servicioSelect = document.getElementById('servicio_select');

    $(clienteSelect).select2();
    $(clienteSelect).on('select2:select', function (e) {
        const clienteId = e.params.data.id;
        
        // Limpia el select de servicios
        $(servicioSelect).empty().append('<option value="">Cargando servicios...</option>');

        if (clienteId) {
            // Realiza la petición AJAX al controlador de CodeIgniter
            fetch(`<?= site_url('getServiciosPorCliente') ?>/${clienteId}`)
                .then(response => response.json()) 
                .then(servicios => {
                    // Limpia y añade las nuevas opciones
                    $(servicioSelect).empty().append('<option value="">Selecciona un servicio</option>');
                    
                    servicios.forEach(servicio => {
                        const option = new Option(servicio.servicio, servicio.id);
                        $(servicioSelect).append(option);
                    });
                    
                    // Asegúrate de que Select2 también se actualice
                    $(servicioSelect).trigger('change');
                })
                .catch(error => {
                    console.error('Error al obtener los servicios:', error);
                    $(servicioSelect).empty().append('<option value="">Error al cargar</option>');
                });
        } else {
            // Si no se selecciona ningún cliente, limpia el select de servicios
            $(servicioSelect).empty().append('<option value="">Selecciona un servicio</option>');
        }
    });
});
  </script>




<?= $this->include('layouts/footerAdvancedForm') ?>
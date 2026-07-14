<?= $this->include('layouts/header') ?>
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
            <h1>Editar Servicio</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('servicios') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Formulario Servicio</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>




 
<form action="<?= base_url('updateServicio/' . $servicio['id']) ?>" method="post" id="formEditServicio">


  
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Servicio</h3> 
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">Cliente</label> 
                  
                  <select name="cliente" id="cliente_id"  class="form-control" disabled>
                      <option value="">Selecciona...</option> 

                      <?php foreach ($clientes as $cliente): ?>
                          <option value="<?= esc($cliente['id']) ?>"
                              <?= ($cliente['id'] == $servicio['cliente']) ? 'selected' : '' ?>>
                              <?= esc($cliente['nombre']) ?>
                          </option>
                      <?php endforeach; ?>
                  </select> 
                </div>   
              </div> 
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">Servicio</label> 
                  <textarea id="inputComentarios" class="form-control" rows="4" name="servicio"> <?= esc($servicio['servicio']) ?> </textarea>
                </div>   
              </div>
              
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">Estatus Servicio</label>  
                    <select name="estatusServ" id="tecnico"  class="form-control">
                      <option value="">Selecciona...</option>  
                      <?php foreach ($estatusservicio as $estatusServ): ?>
                          <option value="<?= esc($estatusServ['id']) ?>"
                              <?= ($estatusServ['id'] == $servicio['estatusServ']) ? 'selected' : '' ?>>
                              <?= esc($estatusServ['estatus']) ?>
                          </option>
                      <?php endforeach; ?>
                    </select>
                </div>   
              </div> 
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div> 

          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Materiales</h3> 
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div> 
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">material</label>
                  <textarea id="inputComentarios" class="form-control" rows="4" name="material"> <?= esc($servicio['material']) ?> </textarea>
                </div>   
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">estatusMat</label>
                  <input type="text" id="inputNombe" name="estatusMat" class="form-control"  value="<?= esc($servicio['estatusMat']) ?>">
                </div>   
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">Cotizacion</label>
                  <input type="text" id="inputNombe" name="cotizacion" class="form-control"  value="<?= esc($servicio['cotizacion']) ?>">
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
                  <label for="inputNombre">estimacionTiempo</label>
                  <input type="text" id="inputNombe" name="estimacionTiempo" class="form-control"  value="<?= esc($servicio['estimacionTiempo']) ?>">
                </div>   
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputNombre">registrante</label>
                  <input type="text" id="registrante" name="registrante" class="form-control"  value="<?= esc($servicio['registrante']) ?>" readonly>
                </div>   
              </div>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div> 

        </div>

        <div class="row">
          <div class="col-12">
            
            <?php if ($estatus_actual != 3): ?>
                <a href="<?= base_url('agendaFormDesdeServicio/' . $servicio['id']) ?>" class="btn btn-success">
                    <i class="fas fa-calendar-plus"></i> Agendar este Servicio
                </a>
            <?php else: ?>
                <span class="badge badge-info p-2">
                    <i class="fas fa-calendar-check"></i> 
                    <?php 
                    foreach ($estatusservicio as $estatus) {
                        if ($estatus['id'] == $estatus_actual) {
                            echo esc($estatus['estatus']);
                            break;
                        }
                    }
                    ?>
                </span>
            <?php endif; ?>          
            <a href="<?= base_url('servicios') ?>" class="btn btn-secondary">Cancelar</a>
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
document.getElementById("formEditServicio").addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
    event.preventDefault(); // evita que se envíe
  }
});
</script>
  
<?= $this->include('layouts/footer') ?>
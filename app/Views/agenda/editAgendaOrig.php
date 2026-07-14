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
            <h1>Editar Agenda</h1>
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
    <form action="<?= base_url('updateAgenda/' . $agenda['id']) ?>" method="post"> 
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
                    <select name="cliente" id="cliente_id"  class="form-control select2" disabled>
                        <option value="">Selecciona...</option>   
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= esc($cliente['id']) ?>"
                                <?= ($cliente['id'] == $agenda['cliente']) ? 'selected' : '' ?>>
                                <?= esc($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                  </div>   
                </div>   
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">tecnico</label> 
                      <select id="tecnicos_select" name="tecnicos[]" multiple="multiple" class="form-control">
                          <?php foreach ($tecnicos as $tecnico): ?>
                              <option 
                                  value="<?= esc($tecnico['id']) ?>"
                                  <?php if (in_array($tecnico['id'], $tecnicosSeleccionados)): ?>
                                      selected="selected"
                                  <?php endif; ?>
                              >
                                  <?= esc($tecnico['nombre']) ?>
                              </option>
                          <?php endforeach; ?>
                      </select>   
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">fechaInicio</label>
                    <input type="datetime-local" id="inputNombe" name="fechaInicio" class="form-control"  value="<?= esc($agenda['fechaInicio']) ?>">
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
                    <label for="inputNombre">Servicio</label>  
                    <select class="form-control" name="servicio" id="servicio"  class="form-control" disabled>
                       
                          <option value="<?= esc($servicio['id']) ?>">
                              <?= esc($servicio['servicio']) ?>
                          </option>
                       
                    </select> 
                   </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Comentarios</label>  
                    <textarea id="inputComentarios" class="form-control" rows="4" name="comentarios"><?= esc($agenda['comentarios']) ?></textarea>
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
<?= $this->include('layouts/footerAdvancedForm') ?>
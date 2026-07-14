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
<!--Codigo modificado 25/09/2025-->                 
                <div class="form-group">
                  <label>Técnicos</label><br>
                  <?php foreach ($tecnicos as $tecnico): ?>
                      <div class="form-check form-check-inline">
                          <input 
                              type="checkbox" 
                              name="tecnicos[]" 
                              value="<?= esc($tecnico['id']) ?>" 
                              id="tec_<?= esc($tecnico['id']) ?>"
                              class="form-check-input"
                              <?= in_array($tecnico['id'], $tecnicosSeleccionados) ? 'checked' : '' ?>
                          >
                          <label class="form-check-label" for="tec_<?= esc($tecnico['id']) ?>">
                              <?= esc($tecnico['nombre']) ?>
                          </label>
                      </div>
                  <?php endforeach; ?>
              </div>
<!--Fin Codigo modificado 25/09/2025-->                 
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">fechaInicio</label>
                    <input type="datetime-local" id="inputNombe" name="fechaInicio" class="form-control"  value="<?= esc($agenda['fechaInicio']) ?>" onkeydown="return event.key !== 'Enter';"> <!--Se agrega onkeydown para deshabilitar el envio del formulario-->
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
                <!-- NUEVO CAMPO: Estado del Servicio -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="estatusServicio">Estado del Servicio</label>  
                    <select name="estatusServicio" id="estatusServicio" class="form-control" required>
                        <option value="">Selecciona el estado...</option>   
                        <?php foreach ($estatusservicio as $estatus): ?>
                            <option value="<?= esc($estatus['id']) ?>"
                                <?= (isset($servicio['estatusServ']) && $estatus['id'] == $servicio['estatusServ']) ? 'selected' : '' ?>>
                                <?= esc($estatus['estatus']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                   </div>   
                </div>
                <!-- FIN NUEVO CAMPO -->

                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Comentarios</label>  
                    <textarea id="inputComentarios" class="form-control" rows="4" name="comentarios"><?= esc($agenda['comentarios']) ?></textarea>
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="prioridad">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-control" required>
                        <option value="">Selecciona...</option>
                        <option value="Alta" <?= ($agenda['prioridad'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                        <option value="Media" <?= ($agenda['prioridad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                        <option value="Baja" <?= ($agenda['prioridad'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
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
                  <h3 class="card-title">Tiempos</h3> 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">estatusPago</label>  
                    <select name="estatusPago" id="estatusPago"  class="form-control">
                        <option value="">Selecciona...</option>   
                        <?php foreach ($estatusPago as $estatusPago1): ?>
                            <option value="<?= esc($estatusPago1['id']) ?>"
                                <?= ($estatusPago1['id'] == $agenda['estatusPago']) ? 'selected' : '' ?>>
                                <?= esc($estatusPago1['estatus']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                   </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">ordenServicio</label>  
                    <input type="text" class="form-control" name="ordenServicio" value="<?= esc($agenda['ordenServicio']) ?>"/> 
                  </div>   
                </div>  
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>  
          </div> 
          <div class="row">
            <div class="col-12">
              <a href="<?= base_url('agenda') ?>" class="btn btn-secondary">Cancelar</a>
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
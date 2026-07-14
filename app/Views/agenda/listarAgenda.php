<?= $this->include('layouts/headerListas') ?>
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
            <h1>Agenda</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Agenda</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12"> 

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Listar agenda</h3>
                <a href="<?= base_url('agendaForm') ?>" class="float-sm-right btn btn-success">Nuevo</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Cliente</th>  
                    <th>servicio</th>
                    <th>Tecnico</th>
                    <th>fecha Inicio</th>
                    <th>Comentarios</th>   
                    <!--th>ordenServicio</th-->
                    <th>Prioridad</th><!--Columna agregada 01/10/2025-->
                    <th>Estatus Pago</th>
                    <th>Acciones</th>  
                  </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($agenda as $agenda): ?>
                    
                    <tr>
                      <td><?= esc($agenda['id']) ?></td>
                      <td><?= esc($agenda['nombre_cliente']) ?></td>
                      <td><?= esc($agenda['servicio_descripcion']) ?></td> 
                      <td> <?= esc(implode(', ', $agenda['nombres_tecnicos'])) ?></td>
                      <td><?= esc($agenda['fechaInicio']) ?></td> 
                      <td><?= esc($agenda['comentarios']) ?></td>   
                      <!--td><?= esc($agenda['ordenServicio']) ?></td-->                        
                      <td><?= esc($agenda['prioridad']) ?></td>
                      <td>
                          <?php if (!empty($agenda['estatus'])): ?>
                              <span class="badge <?= esc($agenda['etiquetas'] ?? '') ?>">
                                  <?= esc($agenda['estatus']) ?>
                              </span>
                          <?php endif; ?>
                      </td>
                      
                      <td>
                            <a href="<?= base_url('editAgenda/' . $agenda['id']) ?>" class="badge bg-primary">Editar</a>
                            <!--a href="<?= base_url('eliminar/' . $agenda['id']) ?>" class="badge bg-danger">Eliminar</a-->
                        </td>
                    </tr> 

                  <?php endforeach; ?>

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Cliente</th>  
                    <th>servicio</th>
                    <th>Tecnico</th>
                    <th>fecha Inicio</th> 
                    <th>Comentarios</th>   
                    <!--th>ordenServicio</th-->
                    <th>Prioridad</th>
                    <th>Estatus Pago</th>
                    <th>Acciones</th>  
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
             
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper --> 
  <?= $this->include('layouts/footerListas') ?>
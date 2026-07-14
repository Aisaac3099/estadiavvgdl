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
            <h1>Clientes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Clientes</li>
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
                <h3 class="card-title">Lista de Clientes</h3>  
                <a href="<?= base_url('clienteForm') ?>" class="float-sm-right btn btn-success">Nuevo</a>

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Correo</th> 
                    <th>Acciones</th>  
                  </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($clientes as $cliente): ?>
                    
                    <tr>
                      <td><?= esc($cliente['id']) ?></td>
                      <td><?= esc($cliente['nombre']) ?></td>
                      <td><?= esc($cliente['direccion']) ?></td>
                      <td><?= esc($cliente['telefono']) ?></td>
                      <td><?= esc($cliente['correo']) ?></td>
                      <td>
                            <a href="<?= base_url('editCliente/' . $cliente['id']) ?>" class="badge bg-primary">Editar</a>
                            
                            <!--a href="<?= base_url('eliminar/' . $cliente['id']) ?>" class="badge bg-danger">Eliminar</a-->
                            <!--a href="<?= base_url('eliminar/' . $cliente['id']) ?>" class="badge bg-danger" onclick="return confirm('¿Seguro que quieres eliminar al cliente <?= addslashes($cliente['nombre']) ?>?')">Eliminar</a-->
                            
                            <a href="<?= base_url('clientes/' . $cliente['id'] . '/servicios') ?>" class="badge bg-info">Servicios</a>
                        </td>
                    </tr> 

                  <?php endforeach; ?>

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Correo</th> 
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
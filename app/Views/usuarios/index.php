<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>   

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuarios</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Usuarios</li>
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
                <h3 class="card-title">Lista de Usuarios</h3>  
                <a href="<?= base_url('usuarios/create') ?>" class="float-sm-right btn btn-success">Nuevo</a>
              </div>

              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre de Usuario</th></th>
                      <th>Email</th>
                      <th>Nombre</th>
                      <th>Permiso</th>
                      <th>Acciones</th>  
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($usuarios as $user): ?>
                    <tr>
                      <td><?= esc($user['id']) ?></td>
                      <td><?= esc($user['nombreUsuario']) ?></td>
                      <td><?= esc($user['email']) ?></td>
                      <td><?= esc($user['nombre']) ?></td>
                      <td><?= esc($user['permiso']) ?></td>
                      <td>
                        <a href="<?= base_url('usuarios/edit/' . $user['id']) ?>" class="badge bg-primary">Editar</a>
                        <a href="<?= base_url('usuarios/delete/' . $user['id']) ?>" class="badge bg-danger" onclick="return confirm('¿Seguro que quieres desactivar este usuario?')">Desactivar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Nombre de Usuario</th>
                      <th>Email</th>
                      <th>Nombre</th>
                      <th>Permiso</th>
                      <th>Acciones</th>  
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
             
          </div>
        </div>
      </div>
    </section>
</div>

<?= $this->include('layouts/footerListas') ?>

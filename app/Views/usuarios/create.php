<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Agregar Usuario</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
              <li class="breadcrumb-item active">Agregar</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12"> 
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Nuevo Usuario</h3>
              </div>

              <form method="post" action="<?= base_url('usuarios/store') ?>">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="nombreUsuario">Nombre de Usuario</label>
                      <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" placeholder="Nombre de Usuario" required>
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" required>
                    </div>

                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo" required>
                    </div>

                    <div class="form-group">
                      <label for="permiso">Permiso</label>
                      <select class="form-control" id="permiso" name="permiso" required>
                        <option value="admin">Admin</option>
                        <option value="tecnico">Técnico</option>
                        <option value="gerencia">Gerencia</option>
                        <option value="administracion">Administración</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="password">Contraseña</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="<?= base_url('usuarios') ?>" class="btn btn-secondary">Cancelar</a>
                  </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>
</div>

<?= $this->include('layouts/footerListas') ?>

<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Editar Usuario</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
              <li class="breadcrumb-item active">Editar</li>
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
                <h3 class="card-title">Editar Usuario</h3>
              </div>

              <form method="post" action="<?= base_url('usuarios/update/'.$usuario['id']) ?>">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="nombreUsuario">Nombre de usuario</label>
                      <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?= esc($usuario['nombreUsuario']) ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?= esc($usuario['email']) ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($usuario['nombre']) ?>" required>
                    </div>

                    <div class="form-group">
                      <label for="permiso">Permiso</label>
                      <select class="form-control" id="permiso" name="permiso" required>
                        <option value="admin" <?= $usuario['permiso'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="tecnico" <?= $usuario['permiso'] == 'tecnico' ? 'selected' : '' ?>>Técnico</option>
                        <option value="gerencia" <?= $usuario['permiso'] == 'gerencia' ? 'selected' : '' ?>>Gerencia</option>
                        <option value="administracion" <?= $usuario['permiso'] == 'administracion' ? 'selected' : '' ?>>Administración</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="password">Contraseña (dejar en blanco si no cambia)</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña">
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-success">Actualizar</button>
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

<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Agregar Técnico</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('tecnicos') ?>">Técnicos</a></li>
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
                <h3 class="card-title">Nuevo Técnico</h3>
              </div>

              <form method="post" action="<?= base_url('tecnicos/agregar') ?>">
                  <div class="card-body">
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del técnico" required>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="<?= base_url('tecnicos') ?>" class="btn btn-secondary">Cancelar</a>
                  </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>
</div>

<?= $this->include('layouts/footerListas') ?>

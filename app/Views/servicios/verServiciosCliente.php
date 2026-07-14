<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Servicios del Cliente</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('clientes') ?>">Clientes</a></li>
              <li class="breadcrumb-item active">Servicios</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3><?= esc($servicios[0]['Cliente'] ?? 'Cliente desconocido') ?></h3>
                <a href="<?= base_url('clientes') ?>" class="float-sm-right btn btn-secondary">Regresar</a>
              </div>

              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID Servicio</th>
                      <th>Servicio</th>
                      <th>Estatus</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($servicios)): ?>
                      <?php foreach ($servicios as $s): ?>
                        <tr>
                          <td><?= esc($s['IdServicio']) ?></td>
                          <td><?= esc($s['servicio']) ?></td>
                          <!--td><?= esc($s['estatus']) ?></td-->
                          <td><span class="badge <?= esc($s['etiquetas']) ?>"><?= esc($s['estatus']) ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3" class="text-center">No hay servicios para este cliente</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>ID Servicio</th>
                      <th>Servicio</th>
                      <th>Estatus</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?= $this->include('layouts/footerListas') ?>

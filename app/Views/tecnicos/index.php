<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>   

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Técnicos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Técnicos</li>
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
                <h3 class="card-title">Lista de Técnicos</h3>  
                <a href="<?= base_url('tecnicos/agregar') ?>" class="float-sm-right btn btn-success">Nuevo</a>
              </div>

              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Nombre</th>
                      <th>Acciones</th>  
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($tecnicos as $tec): ?>
                    <tr>
                      <td><?= esc($tec['id']) ?></td>
                      <td><?= esc($tec['nombre']) ?></td>
                      <td>
                        <a href="<?= base_url('tecnicos/editar/' . $tec['id']) ?>" class="badge bg-primary">Editar</a>
                        
                        <a href="<?= base_url('tecnicos/eliminar/' . $tec['id']) ?>" class="badge bg-danger">Eliminar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Id</th>
                      <th>Nombre</th>
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

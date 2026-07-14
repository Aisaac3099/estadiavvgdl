<?= $this->include('layouts/header') ?>
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
            <h1>Editar Cliente</h1>
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
  <form action="<?= base_url('updateCliente/' . $cliente['id']) ?>" method="post" id="EditCliente"> 
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Clientes</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="inputNombre">Nombre</label>
                <input type="text" id="inputNombe" name="nombre" class="form-control"  value="<?= esc($cliente['nombre']) ?>">
              </div>  
              <div class="form-group">
                <label for="inputDireccion">Direccion</label>
                <input type="text" id="inputDireccion" name="direccion" class="form-control"  value="<?= esc($cliente['direccion']) ?>">
              </div>
              <div class="form-group">
                <label for="inputTelefono">Telefono</label>
                <input type="text" id="inputTelefono" name="telefono" class="form-control"  value="<?= esc($cliente['telefono']) ?>">
              </div>
              <div class="form-group">
                <label for="inputCorreo">Correo</label>
                <input type="text" id="inputCorreo" name="correo" class="form-control"  value="<?= esc($cliente['correo']) ?>">
              </div>
               
              <div class="form-group">
                <label for="inputComentarios">Comentarios</label>
                <textarea id="inputComentarios" class="form-control" rows="4" name="comentarios"> <?= esc($cliente['comentarios']) ?></textarea>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div> 
      </div>
      </div>
      <div class="row">
        <div class="col-12">
          <a href="<?= base_url('clientes') ?>" class="btn btn-secondary">Cancel</a>
          <input type="submit" value="Guardar" class="btn btn-success float-right">
        </div>
      </div>
    </section>
    <!-- /.content -->

    <script>
    document.getElementById("EditCliente").addEventListener("keydown", function(event) {
      if (event.key === "Enter") {
        event.preventDefault(); // evita que se envíe
      }
    });
    </script>
</form>




    
  </div>
  <!-- /.content-wrapper -->

  
<?= $this->include('layouts/footer') ?>
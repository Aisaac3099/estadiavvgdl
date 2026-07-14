<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema VVGDL</title> 
  <link rel="icon" type="image/png" href="<?= base_url('favicon.png') ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
</head>
 
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="<?= base_url('logo.png') ?>" alt="" class="img-fluid"> <!--img fliud para centrar la imagen o se adapte-->
    <a href="#"><b> SISTEMA </b>VVGDL</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicia sesión para comenzar tu sesión</p>

      <form action="<?= base_url('login') ?>" method="post"> 
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="login" placeholder="Nombre de Usuario o Correo" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-users"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row"> 
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-block btn-primary">Iniciar Sesión</button>
          </div>
          <!-- /.col -->
        </div>
      </form> 
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
</body>
</html>

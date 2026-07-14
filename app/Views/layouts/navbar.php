  <?php $session = session(); ?>
  <?php helper('notificacion');?>
  <?php 
  $session = session();
  $notificacionService = service('notificaciones');
  $notificaciones = $notificacionService->paraNavbar();

  $totalNotificaciones = $notificacionService->contarParaNavbar();
  ?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= base_url('/agenda') ?>" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <?php if ($session->get('isLoggedIn')): ?>
            <a href="<?= base_url('logout') ?>" class="nav-link">Cerrar Sesión</a>
        <?php else: ?>
            <a href="<?= base_url('login') ?>" class="nav-link">Iniciar Sesión</a>
        <?php endif; ?> 
      </li>
    </ul>







    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto"> 
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class= "badge badge-danger navbar-badge"><?=$totalNotificaciones > 99 ? '99+' : $totalNotificaciones?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
            <?= $totalNotificaciones?> Notificaciones
          </span>
          <div class="dropdown-divider"></div>
          
          <?php if (!empty($notificaciones)):?>
            <?php foreach ($notificaciones as $notificacion):?>
              <?php
              $modulo = $notificacion['modulo'] ?? 'general';
              $nombreModulo = match ($modulo){
                'servicios' => 'Servicios',
                'agenda' => 'Agenda',
                'servicios_autos' =>'Servicios de Autos',
                default => 'General'
              }; ?>
              <a href="<?= esc($notificacion['url'])?>" class="dropdown-item">
              <div class="mb-1">
                <span class="badge badge-<?=esc($notificacion['color'])?>">
                  <?=esc($nombreModulo)?>
                </span>
              </div>
              <div>
                <i class="<?= esc($notificacion['icono'])?> mr-2 text-<?=esc($notificacion['color'])?>"></i>
                <strong><?=esc($notificacion['titulo'])?></strong>
              </div>
                <span class="float-right text-muted text-sm">
                  <?= esc(tiempoNotificacion($notificacion['fecha']))?>
                </span>
                <br>
                <small class="text-muted">
                  <?=esc($notificacion['mensaje'])?>
                </small>
              </a>
              <div class="dropdown-divider"></div>
              <?php endforeach;?>
              <?php else:?>
                <span class="dropdown-item text-center text-muted">No Hay Notificaciones.</span>
                <?php endif;?>
          
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->



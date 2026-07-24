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
        <!-- Isaac Gonzalez Agrego para notificaciones: ajustar la bandeja para contener textos largos sin ocultar información -->
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right bandeja-notificaciones">
          <span class="dropdown-item dropdown-header">
            <?= $totalNotificaciones?> Notificaciones
          </span>
          <div class="dropdown-divider"></div>
          
          <div class="notificaciones-lista">
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
              <a href="<?= esc($notificacion['url'])?>" class="dropdown-item notificacion-item">
              <div class="mb-1 notificacion-etiqueta">
                <span class="badge badge-<?=esc($notificacion['color'])?>">
                  <?=esc($nombreModulo)?>
                </span>
              </div>
              <div class="d-flex notificacion-encabezado">
                <i class="<?= esc($notificacion['icono'])?> mr-2 mt-1 text-<?=esc($notificacion['color'])?>"></i>
                <div class="notificacion-contenido">
                  <div class="d-flex flex-wrap justify-content-between notificacion-meta">
                    <strong class="notificacion-titulo"><?=esc($notificacion['titulo'])?></strong>
                    <?php if (empty($notificacion['dinamica'])):?>
                    <span class="text-muted text-sm notificacion-fecha">
                      <?= esc(tiempoNotificacion($notificacion['fecha']))?>
                    </span>
                    <?php endif;?>
                  </div>
                  <small class="text-muted notificacion-mensaje">
                    <?=esc($notificacion['mensaje'])?>
                  </small>
                </div>
              </div>
              </a>
              <div class="dropdown-divider"></div>
              <?php endforeach;?>
              <?php else:?>
                <span class="dropdown-item text-center text-muted">No Hay Notificaciones.</span>
                <?php endif;?>
          </div>
          
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

  <style>
    /* Isaac Gonzalez Agrego para notificaciones: ajustar la bandeja para contener textos largos sin ocultar información */
    .bandeja-notificaciones {
      width: 380px;
      max-width: calc(100vw - 20px);
      overflow-x: hidden;
    }

    .bandeja-notificaciones .dropdown-header {
      white-space: normal;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .notificaciones-lista {
      max-height: 70vh;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .notificacion-item {
      white-space: normal;
      overflow: hidden;
    }

    .notificacion-encabezado,
    .notificacion-meta {
      min-width: 0;
    }

    .notificacion-contenido {
      flex: 1;
      min-width: 0;
      width: 100%;
    }

    .notificacion-titulo,
    .notificacion-fecha,
    .notificacion-mensaje,
    .notificacion-etiqueta {
      white-space: normal;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .notificacion-titulo {
      min-width: 0;
      max-width: 100%;
      padding-right: .5rem;
    }

    .notificacion-fecha {
      margin-left: auto;
    }

    .notificacion-mensaje {
      display: block;
      width: 100%;
      margin-top: .25rem;
    }

    @media (max-width: 576px) {
      .bandeja-notificaciones {
        width: calc(100vw - 20px);
        max-width: calc(100vw - 20px);
      }
    }
  </style>



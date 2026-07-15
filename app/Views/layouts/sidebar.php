 <?php $session = session(); ?>
  
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('calendario') ?>" class="brand-link">
      <img src="<?= base_url('dist/img/ojitoBlanco.png') ?>" alt="AVVGDL sistema" class="brand-image" style="opacity: .8">
      
      <span class="brand-text font-weight-light">VVGDL sistema</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex"> 
        <div class="info">
          <a  href="<?= base_url('calendario') ?>" class="d-block"> 
            <?php if ($session->has('nombre')): ?>
              <p><?= esc($session->get('nombre')) ?> <br/>
               <?= esc($session->get('email')) ?></p> 
            <?php endif; ?>
          </a>
        </div>

       




      </div>

      <!-- SidebarSearch Form 
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="<?= base_url('clientes') ?>" class="nav-link"> 
              <i class="fas fa-user-tie"></i>
              <p>
                Clientes
              </p>
            </a>  
          </li> 
          <li class="nav-item">
            <a href="<?= base_url('servicios') ?>" class="nav-link"> 
              <i class="fas fa-book-dead"></i> 
              <p>
                Servicios
              </p>
            </a>  
          </li> 
          <li class="nav-item">
            <a href="<?= base_url('agenda') ?>" class="nav-link"> 
              <i class="fas fa-book"></i> 
              <p>
                Agenda
              </p>
            </a>  
          </li> 
          <li class="nav-item">
            <a href="<?= base_url('calendario') ?>" class="nav-link">
              <i class="far fa-calendar-alt"></i>
              <p>
                Calendario
              </p>
            </a>  
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">   
              <i class="fas fa-car"></i>  
              <p>Servicios de Autos</p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=base_url('autos')?>" class="nav-link">
                  <i class="fas fa-car nav-icon"></i>
                  <p>Autos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=base_url('servicios_autos')?>" class="nav-link">
                  <i class="fas fa-book-dead nav-icon"></i>
                  <p>Servicios</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Isaac Gonzalez Agrego para inventario: acceso directo al módulo desde la barra lateral -->
          <li class="nav-item">
            <a href="<?= base_url('inventario') ?>" class="nav-link">
              <i class="fas fa-boxes"></i>
              <p>
                Inventario
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('tecnicos') ?>" class="nav-link">   
              <i class="fas fa-hard-hat"></i>  
              <p>
                Tecnicos
              </p>
            </a>  
          </li> 
          <li class="nav-item">
            <a href="<?= base_url('usuarios') ?>" class="nav-link">
              <i class="fas fa-users"></i>  
              <p>
                Usuarios
              </p>
            </a>  
          </li> 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

<style>
    .nav-sidebar .has-treeview:hover > .nav-treeview{
      display:block !important;
    }
  </style>


  </aside>

  




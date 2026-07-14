<?= $this->include('layouts/headerAdvancedForm') ?>
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
            <h1>Agregar Servicio</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('agenda') ?>">Inicio</a></li>
              <li class="breadcrumb-item active">Formulario de servicio</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>






<form action="<?= base_url('/servicioForm') ?>" method="post" id="formServicio">


    <!-- Main content -->
    <section class="content">
      
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Servicio</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Cliente</label>  
                    <select name="cliente" class="form-control select2" id="cliente" required> 
                        <option value="">Selecciona...</option>   
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= esc($cliente['id']) ?>">
                                <?= esc($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Servicio</label> 
                    <textarea name="servicio" id="" class="form-control" rows="4"></textarea>
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">Estatus Servicio</label> 
                    <select name="estatusServ" id="cliente_id"  class="form-control" required>
                        <option value="">Selecciona...</option>   
                        <?php foreach ($estatus as $estatus): ?>
                            <option value="<?= esc($estatus['id']) ?>">
                                <?= esc($estatus['estatus']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                  </div>   
                </div> 
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div> 
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Servicio</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">cotizacion</label>
                    <input type="text" id="inputNombe" name="cotizacion" class="form-control" >
                  </div>   
                </div> 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputNombre">material</label>
                     <textarea name="material" id="" class="form-control" rows="4"></textarea> 
                  </div>   
                </div>  
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div> 
          
        </div>
        <div class="row">
          <div class="col-12">
            <a href="<?= base_url('servicios') ?>" class="btn btn-secondary">Cancelar</a>
            <input type="submit" value="Guardar" class="btn btn-success float-right">
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->

</form>




    
  </div>
  <!-- /.content-wrapper -->

<script>
document.getElementById("formServicio").addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
    event.preventDefault(); // evita que se envíe
  }
});
</script>

<!-- FIX: Se añadieron los scripts necesarios de Firebase SDK (V8) justo antes de la lógica de la app. -->
<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-messaging.js"></script>

<script>
    /**
     * Lógica de Firebase Cloud Messaging (FCM) para registrar el token en el servidor.
     * Esta lógica está integrada en la vista para acceder al user_id de la sesión PHP.
     */
    
    // Función principal que contiene toda la lógica de FCM
    function initFCM() {
        
        // 1. OBTENER ID DE USUARIO DESDE PHP Y ENDPOINT
        // ***********************************************************************************
        // Clave de sesión cambiada a 'user_id' para probar
        const CURRENT_USER_ID = <?= json_encode(session('user_id') ?? 'NO_USER_ID') ?>; 
        // Endpoint de CodeIgniter que guarda el token (según conversaciones previas)
        //const FCM_TOKEN_ENDPOINT = '<?= base_url('fcm/registrar') ?>';
        const FCM_TOKEN_ENDPOINT = '<?= base_url('servicio/guardarToken') ?>'; //agregado 14-10-25
        // VAPID Key obtenida de tu archivo fcm_handler.js
        const VAPID_KEY = 'BHkeYRbi6Dg31Cwj7OGG7K6-U2OMzPPub-2gZYw_dIRowsunBoAjiS2hdkm2v9xL4J_28QuFSN0ujuRnb91aFdM';
        
        const statusMessage = document.getElementById('fcm-status-message');
        
        // --- DEPURACIÓN ---
        console.log('FCM Debug: Valor de session(\'user_id\') obtenido:', CURRENT_USER_ID);
        // --- FIN DE DEPURACIÓN ---

        // Validación inicial del ID de usuario
        if (CURRENT_USER_ID === 'NO_USER_ID' || CURRENT_USER_ID === null || CURRENT_USER_ID === '') {
            console.error('FCM Error: No se pudo obtener el ID del usuario actual. El token NO se registrará.');
            if (statusMessage) {
                statusMessage.innerHTML = '<span class="text-danger">❌ Error de autenticación: ID de usuario no disponible.</span>';
            }
            return;
        }

        // 2. CONFIGURACIÓN DE FIREBASE (VALORES REALES)
        // ***********************************************************************************
        const firebaseConfig = {
            apiKey: "AIzaSyCPZUJtdDbbsN2XO05MTvvhB9yH4w2Uxnk", 
            authDomain: "sistemavvgdl-f3701.firebaseapp.com",
            projectId: "sistemavvgdl-f3701", 
            storageBucket: "sistemavvgdl-f3701.firebasestorage.app",
            messagingSenderId: "1042217658941", 
            appId: "1:1042217658941:web:b64ba1d18d02dfac395854" 
        };
        
        // Inicialización y obtención del objeto 'messaging'
        let messaging;
        try {
            // Se comprueba que la librería de FCM se haya cargado correctamente.
            if (typeof firebase.messaging === 'undefined') {
                 // Si falla, muestra un error en consola, pero dejamos que continúe el try/catch.
                 console.error("firebase.messaging() no está definido en el contexto global.");
            }
            firebase.initializeApp(firebaseConfig);
            messaging = firebase.messaging();
            console.log("Firebase app initialized and messaging service ready.");

        } catch (e) {
            console.error("Error al inicializar Firebase o al obtener el servicio de mensajería: ", e);
            if (statusMessage) {
                statusMessage.innerHTML = '<span class="text-danger">❌ Error de configuración de Firebase: ' + e.message + '</span>';
            }
            return;
        }

        // 2.5. Validación de la función crítica de FCM (Nueva Verificación de Robustez)
        if (typeof messaging.requestPermission !== 'function') {
            console.error('FCM Critical Error: La función messaging.requestPermission no está definida. Esto confirma un problema de carga asíncrona no resuelto o configuración de Service Worker.');
            if (statusMessage) {
                statusMessage.innerHTML = '<span class="text-danger">❌ Error Crítico de FCM: El SDK de mensajería no cargó la función necesaria.</span>';
            }
            return;
        }


        // 3. SOLICITAR PERMISOS Y OBTENER EL TOKEN
        messaging.requestPermission().then(function() {
            console.log('Permiso de notificación concedido.');
            if (statusMessage) {
                statusMessage.innerHTML = '<span class="text-info">🔔 Permiso de notificación concedido. Obteniendo token...</span>';
            }
            
            // Obtener el token usando la VAPID Key
            return messaging.getToken({ vapidKey: VAPID_KEY });
        }).then(function(token) {
            if (token) {
                console.log('FCM Token obtenido:', token.substring(0, 20) + '...');
                // 4. ENVIAR EL TOKEN AL BACKEND DE CODEIGNITER
                sendTokenToServer(token);
            } else {
                console.log('No se obtuvo el token.');
                if (statusMessage) {
                    statusMessage.innerHTML = '<span class="text-warning">⚠️ No se pudo generar el token de registro.</span>';
                }
            }
        }).catch(function(err) {
            console.error('Error al solicitar permiso o al obtener el token: ', err);
            if (statusMessage) {
                statusMessage.innerHTML = '<span class="text-warning">⚠️ Permiso denegado o error de Firebase.</span>';
            }
        });

        // 5. MANEJO DE ACTUALIZACIÓN DEL TOKEN (ESENCIAL)
        messaging.onTokenRefresh(function() {
            messaging.getToken({ vapidKey: VAPID_KEY }).then(function(refreshedToken) {
                console.log('Token refrescado. Enviando al servidor...');
                sendTokenToServer(refreshedToken);
            }).catch(function(err) {
                console.error('Error al recuperar token refrescado: ', err);
            });
        });


        // 6. FUNCIÓN CENTRAL PARA ENVIAR EL TOKEN AL BACKEND
        function sendTokenToServer(fcmToken) {
            // Obtenemos el token CSRF del meta tag para seguridad en CodeIgniter
            // Nota: Debes asegurar que tu vista de Header incluya: <meta name="X-CSRF-TOKEN" content="[TU_TOKEN_AQUI]">
            const csrfTokenElement = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            const csrfToken = csrfTokenElement ? csrfTokenElement.content : '';

            if (!csrfToken) {
                 console.error('CSRF Error: No se encontró el token CSRF en la meta etiqueta. La petición fallará.');
                 if (statusMessage) {
                    statusMessage.innerHTML = '<span class="text-danger">❌ Error CSRF: No se encontró el token de seguridad.</span>';
                 }
                 return;
            }
            
            fetch(FCM_TOKEN_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Incluir el token CSRF en el header para peticiones seguras
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({
                    fcm_token: fcmToken,
                    user_id: CURRENT_USER_ID // Enviamos el ID de usuario de CodeIgniter
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    console.log('✅ Registro de Token FCM exitoso para el usuario ' + CURRENT_USER_ID);
                    if (statusMessage) {
                        statusMessage.innerHTML = '<span class="text-success">✅ Token de notificación registrado correctamente.</span>';
                    }
                } else {
                    console.error('❌ Error de registro en el backend:', data.message);
                    if (statusMessage) {
                        statusMessage.innerHTML = `<span class="text-danger">❌ Error de registro en DB: ${data.message}</span>`;
                    }
                }
            })
            .catch(error => {
                console.error('Error durante la comunicación con el servidor:', error);
                if (statusMessage) {
                    statusMessage.innerHTML = `<span class="text-danger">❌ Error de conexión al servidor: ${error.message}</span>`;
                }
            });
        }
    }

    // Aumentamos el retraso a 500ms para asegurar que los módulos de Firebase estén completamente inicializados
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initFCM, 500);
    });
</script>
  


<?= $this->include('layouts/footerAdvancedForm') ?> 
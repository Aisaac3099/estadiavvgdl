// ============== 1. CONFIGURACIÓN DE FIREBASE ==============

// REEMPLAZA ESTO con tu objeto de configuración de Firebase
const firebaseConfig = {
  apiKey: "AIzaSyC47ppNIMUWSGVrLAkraTC5VhuJTkOYVTc",
  authDomain: "vvgdl-28e9b.firebaseapp.com",
  projectId: "vvgdl-28e9b",
  storageBucket: "vvgdl-28e9b.firebasestorage.app",
  messagingSenderId: "369320193399",
  appId: "1:369320193399:web:90dab0017b16f88aaf0572"
};
 




// Inicializar Firebase
firebase.initializeApp(firebaseConfig);

// Obtener el servicio de Messaging
const messaging = firebase.messaging();

messaging.getToken({ vapidKey: 'BBhTSWopd0ZslaH9NdsrMYijmP0s1LaHzEMgZuFURmHB_PSOjimFLiN2xZ0-IQ1tUWsFIlPQcu0c7YJykjITnqU' })
    .then(token => console.log('¡Token Manual Obtenido!', token))
    .catch(err => console.error('Error de Firebase:', err));
    
// ============== 2. SOLICITAR PERMISOS Y OBTENER TOKEN ==============

function requestPermissionAndGetToken() {
    console.log('Solicitando permiso de notificación...');
    
    // Solicitar permiso de notificación al navegador
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('Permiso de notificación concedido.');
            
            // Obtener el token de registro del dispositivo
            messaging.getToken({ vapidKey: 'BBhTSWopd0ZslaH9NdsrMYijmP0s1LaHzEMgZuFURmHB_PSOjimFLiN2xZ0-IQ1tUWsFIlPQcu0c7YJykjITnqU' }) // La VAPID Key está en Configuración > Cloud Messaging
                .then((currentToken) => {
                    if (currentToken) {
                        console.log('FCM Token obtenido:', currentToken);
                        // ¡Paso clave! Enviar el token al backend de CodeIgniter
                        sendTokenToServer(currentToken);
                    } else {
                        console.log('No se obtuvo el token. ¿Tienes el permiso de notificación?');
                    }
                }).catch((err) => {
                    console.error('Error al obtener el token: ', err);
                });
        } else {
            console.log('Permiso de notificación denegado.');
        }
    });
}


// ============== 3. ENVIAR TOKEN AL BACKEND DE CI4 ==============

function sendTokenToServer(token) {
    // URL del controlador de CodeIgniter que creaste para guardar el token
    const url = '/servicios/guardarToken'; 

    // Usamos el API fetch para enviar el token a CodeIgniter
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // CodeIgniter 4 requiere el header CSRF para peticiones POST seguras.
            // Asegúrate de que esta variable esté definida en tu vista HTML.
            'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content')
        },
        body: JSON.stringify({ fcm_token: token })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('FCM Token guardado/actualizado en CodeIgniter.');
        } else {
            console.error('Error al guardar el token en el servidor:', data.msg);
        }
    })
    .catch(error => {
        console.error('Error de red al enviar el token:', error);
    });
}

// Iniciar el proceso cuando la página esté completamente cargada
requestPermissionAndGetToken();


// ============== 4. MANEJO DE NOTIFICACIONES RECIBIDAS (Opcional) ==============

// Esto es para manejar las notificaciones cuando el usuario está activamente en la página
messaging.onMessage((payload) => {
    console.log('Mensaje recibido en primer plano:', payload);
    
    // Muestra una notificación personalizada si es necesario
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/favicon.ico' // Usa el icono de tu proyecto
    };
    new Notification(notificationTitle, notificationOptions);
});
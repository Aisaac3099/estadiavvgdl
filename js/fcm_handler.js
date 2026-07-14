// ============== 1. CONFIGURACIÓN DE FIREBASE ==============

// REEMPLAZA ESTO con tu objeto de configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyCPZUJtdDbbsN2XO05MTvvhB9yH4w2Uxnk",
    authDomain: "sistemavvgdl-f3701.firebaseapp.com",
    projectId: "sistemavvgdl-f3701",
    storageBucket: "sistemavvgdl-f3701.firebasestorage.app",
    messagingSenderId: "1042217658941",
    appId: "1:1042217658941:web:b64ba1d18d02dfac395854"
};

// Inicializar Firebase
firebase.initializeApp(firebaseConfig);

// Obtener el servicio de Messaging
const messaging = firebase.messaging();

// ============== 2. SOLICITAR PERMISOS Y OBTENER TOKEN ==============

function requestPermissionAndGetToken() {
    console.log('🔔 Solicitando permiso de notificación...');
    
    // Solicitar permiso de notificación al navegador
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('✅ Permiso de notificación concedido.');
            
            // Obtener el token de registro del dispositivo
            messaging.getToken({ vapidKey: 'BHkeYRbi6Dg31Cwj7OGG7K6-U2OMzPPub-2gZYw_dIRowsunBoAjiS2hdkm2v9xL4J_28QuFSN0ujuRnb91aFdM' })
                .then((currentToken) => {
                    if (currentToken) {
                        console.log('✅ FCM Token obtenido:', currentToken.substring(0, 20) + '...');
                        // ¡Paso clave! Enviar el token al backend de CodeIgniter
                        sendTokenToServer(currentToken);
                    } else {
                        console.log('❌ No se obtuvo el token. ¿Tienes el permiso de notificación?');
                    }
                }).catch((err) => {
                    console.error('❌ Error al obtener el token: ', err);
                });
        } else {
            console.log('❌ Permiso de notificación denegado.');
        }
    });
}

// ============== 3. ENVIAR TOKEN AL BACKEND DE CI4 ==============

function sendTokenToServer(token) {
    // CORREGIDO: Usar la ruta correcta (probablemente '/servicio/guardarToken')
    const url = '/servicio/guardarToken'; 

    // Obtener user_id - ESTO ES CRÍTICO
    let userId = obtenerUserId();

    // DEBUG en consola
    console.log('📡 Enviando token al servidor...');
    console.log('🔔 Token:', token.substring(0, 20) + '...');
    console.log('👤 UserID:', userId);

    // CORREGIDO: Enviar user_id en el request
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // CSRF alternativo por si falla
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            fcm_token: token,
            user_id: userId // ← ESTO ES LO MÁS IMPORTANTE
        })
    })
    .then(response => {
        console.log('📡 Status de respuesta:', response.status);
        console.log('📡 URL llamada:', url);
        return response.json();
    })
    .then(data => {
        console.log('📡 Respuesta JSON del servidor:', data);
        if (data.status === 'success') {
            console.log('✅ FCM Token guardado correctamente en servidor');
            console.log('🔔 Total tokens activos:', data.total_tokens);
            
            // Mostrar notificación de éxito al usuario
            mostrarNotificacion('success', 'Notificaciones activadas correctamente');
        } else {
            console.error('❌ Error del servidor:', data.msg);
            mostrarNotificacion('error', 'Error al activar notificaciones: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        mostrarNotificacion('error', 'Error de conexión al activar notificaciones');
    });
}

// ============== FUNCIÓN PARA OBTENER USER_ID ==============

function obtenerUserId() {
    // OPCIÓN 1: Si tienes el user_id en una variable global
    if (typeof currentUserId !== 'undefined') {
        console.log('👤 UserID obtenido de variable global:', currentUserId);
        return currentUserId;
    }
    
    // OPCIÓN 2: Si está en un elemento HTML
    const userIdElement = document.getElementById('user_id');
    if (userIdElement && userIdElement.value) {
        console.log('👤 UserID obtenido de elemento HTML:', userIdElement.value);
        return userIdElement.value;
    }
    
    // OPCIÓN 3: Si está en un meta tag
    const metaUserId = document.querySelector('meta[name="user_id"]');
    if (metaUserId && metaUserId.getAttribute('content') !== 'unknown') {
        console.log('👤 UserID obtenido de meta tag:', metaUserId.getAttribute('content'));
        return metaUserId.getAttribute('content');
    }
    
    // OPCIÓN 4: Si está en un data attribute del body
    const bodyUserId = document.body.getAttribute('data-user-id');
    if (bodyUserId && bodyUserId !== 'unknown') {
        console.log('👤 UserID obtenido de data attribute:', bodyUserId);
        return bodyUserId;
    }
    
    // OPCIÓN 5: Generar uno temporal por dispositivo
    let deviceId = localStorage.getItem('fcm_device_id');
    if (!deviceId) {
        deviceId = 'device_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('fcm_device_id', deviceId);
    }
    
    console.warn('⚠️ No se encontró user_id de sesión, usando device_id:', deviceId);
    return deviceId;
}

// ============== FUNCIÓN PARA MOSTRAR NOTIFICACIONES ==============

function mostrarNotificacion(tipo, mensaje) {
    // Puedes usar tu sistema de notificaciones existente o crear uno simple
    console.log('💬 Notificación:', tipo, '-', mensaje);
    
    // Ejemplo simple con alerta nativa (puedes personalizar esto)
    if (tipo === 'error') {
        alert('❌ ' + mensaje);
    } else if (tipo === 'success') {
        // Opcional: mostrar alerta de éxito
        // alert('✅ ' + mensaje);
    }
}

// ============== MANEJO DE ERRORES DE FCM ==============

function setupFCMErrorHandling() {
    // Manejar errores de FCM
    messaging.onTokenError((error) => {
        console.error('❌ Error de token FCM:', error);
    });
    
    // Manejar errores generales de messaging
    messaging.onError((error) => {
        console.error('❌ Error general de FCM:', error);
    });
}

// ============== 4. MANEJO DE NOTIFICACIONES RECIBIDAS ==============

// Esto es para manejar las notificaciones cuando el usuario está activamente en la página
messaging.onMessage((payload) => {
    console.log('📨 Mensaje recibido en primer plano:', payload);
    
    // Muestra una notificación personalizada
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/favicon.ico', // Usa el icono de tu proyecto
        badge: '/favicon.ico'
    };
    
    // Mostrar notificación nativa
    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(notificationTitle, notificationOptions);
    }
    
    // Opcional: Mostrar notificación personalizada en la UI
    mostrarNotificacionEnUI(payload);
});

// ============== FUNCIÓN PARA MOSTRAR NOTIFICACIÓN EN UI ==============

function mostrarNotificacionEnUI(payload) {
    // Crear elemento de notificación en la UI
    const notificationDiv = document.createElement('div');
    notificationDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #fff;
        border-left: 4px solid #007bff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 10000;
        max-width: 300px;
    `;
    
    notificationDiv.innerHTML = `
        <strong>${payload.notification.title}</strong>
        <p style="margin: 5px 0 0 0; color: #666;">${payload.notification.body}</p>
        <button onclick="this.parentElement.remove()" style="float: right; background: none; border: none; cursor: pointer;">×</button>
    `;
    
    document.body.appendChild(notificationDiv);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (notificationDiv.parentElement) {
            notificationDiv.remove();
        }
    }, 5000);
}

// ============== INICIALIZACIÓN MEJORADA ==============

function initFCM() {
    console.log('🚀 Inicializando FCM...');
    
    // Verificar si Firebase Messaging es soportado
    if (!firebase.messaging.isSupported()) {
        console.warn('⚠️ FCM no es soportado en este navegador');
        mostrarNotificacion('error', 'Tu navegador no soporta notificaciones push');
        return;
    }
    
    // Verificar si ya tenemos permisos
    if (Notification.permission === 'granted') {
        console.log('✅ Permisos ya concedidos, obteniendo token...');
        messaging.getToken({ vapidKey: 'BHkeYRbi6Dg31Cwj7OGG7K6-U2OMzPPub-2gZYw_dIRowsunBoAjiS2hdkm2v9xL4J_28QuFSN0ujuRnb91aFdM' })
            .then((currentToken) => {
                if (currentToken) {
                    console.log('✅ Token FCM obtenido (permisos ya concedidos):', currentToken.substring(0, 20) + '...');
                    sendTokenToServer(currentToken);
                }
            })
            .catch((err) => {
                console.error('❌ Error al obtener token:', err);
            });
    } else if (Notification.permission === 'denied') {
        console.warn('❌ Permisos de notificación denegados por el usuario');
        mostrarNotificacion('error', 'Los permisos de notificación están denegados. Por favor, habilítalos en la configuración de tu navegador.');
    } else {
        // Solicitar permisos cuando la página cargue
        console.log('🔔 Permisos no solicitados aún, esperando carga de página...');
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', requestPermissionAndGetToken);
        } else {
            setTimeout(requestPermissionAndGetToken, 1000); // Pequeño delay para mejor UX
        }
    }
    
    // Configurar manejo de errores
    setupFCMErrorHandling();
}

// ============== FUNCIONES GLOBALES PARA USO EXTERNO ==============

// Función para forzar la renovación del token (útil después de login)
window.renovarTokenFCM = function() {
    console.log('🔄 Forzando renovación de token FCM...');
    messaging.getToken({ vapidKey: 'BHkeYRbi6Dg31Cwj7OGG7K6-U2OMzPPub-2gZYw_dIRowsunBoAjiS2hdkm2v9xL4J_28QuFSN0ujuRnb91aFdM' })
        .then((currentToken) => {
            if (currentToken) {
                console.log('✅ Nuevo token FCM obtenido:', currentToken.substring(0, 20) + '...');
                sendTokenToServer(currentToken);
            }
        })
        .catch((err) => {
            console.error('❌ Error al renovar token:', err);
        });
};

// ============== INICIAR FCM CUANDO LA PÁGINA ESTÉ LISTA ==============

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFCM);
} else {
    initFCM();
}

// ============== MANEJO DE ACTUALIZACIÓN DE TOKENS ==============

// Escuchar cuando el token cambie (renovación automática)
messaging.onTokenRefresh(() => {
    console.log('🔄 Token FCM actualizándose...');
    messaging.getToken({ vapidKey: 'BHkeYRbi6Dg31Cwj7OGG7K6-U2OMzPPub-2gZYw_dIRowsunBoAjiS2hdkm2v9xL4J_28QuFSN0ujuRnb91aFdM' })
        .then((currentToken) => {
            if (currentToken) {
                console.log('✅ Token FCM actualizado:', currentToken.substring(0, 20) + '...');
                sendTokenToServer(currentToken);
            }
        })
        .catch((err) => {
            console.error('❌ Error al actualizar token:', err);
        });
});
/**
 * Service Worker para Firebase Cloud Messaging (FCM).
 * Este archivo DEBE estar en la raíz pública del sitio.
 */

// 1. IMPORTAR LIBRERÍAS (Firebase SDK v8)
importScripts('https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.6.2/firebase-messaging.js');

// 2. CONFIGURACIÓN DE FIREBASE (MISMOS VALORES QUE EN servicioForm.php)
const firebaseConfig = {
    apiKey: "AIzaSyCPZUJtdDbbsN2XO05MTvvhB9yH4w2Uxnk", 
    authDomain: "sistemavvgdl-f3701.firebaseapp.com",
    projectId: "sistemavvgdl-f3701", 
    storageBucket: "sistemavvgdl-f3701.firebasestorage.app",
    messagingSenderId: "1042217658941", 
    appId: "1:1042217658941:web:b64ba1d18d02dfac395854" 
};

// 3. INICIALIZAR LA APLICACIÓN DE FIREBASE
firebase.initializeApp(firebaseConfig);

// 4. OBTENER EL OBJETO MESSAGING
const messaging = firebase.messaging();


// 5. LÓGICA PARA RECIBIR MENSAJES EN BACKGROUND (Cuando la pestaña está cerrada o inactiva)
// Esta función es la que muestra la notificación en el sistema operativo.
messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Mensaje de fondo recibido. ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/tu-icono.png' // CAMBIA ESTO A UNA RUTA DE ICONO REAL DE TU APP
        // Puedes añadir más opciones: badge, data, click_action, etc.
    };

    // Muestra la notificación al usuario.
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

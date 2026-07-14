// Contenido de public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js');

// REEMPLAZA ESTO con tu objeto de configuración
const firebaseConfig = {
  apiKey: "AIzaSyC47ppNIMUWSGVrLAkraTC5VhuJTkOYVTc",
  authDomain: "vvgdl-28e9b.firebaseapp.com",
  projectId: "vvgdl-28e9b",
  storageBucket: "vvgdl-28e9b.firebasestorage.app",
  messagingSenderId: "369320193399",
  appId: "1:369320193399:web:90dab0017b16f88aaf0572"
};
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
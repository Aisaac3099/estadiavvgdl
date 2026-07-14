<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$archivo = '/home1/videovig/sistema.videovigilanciagdl.com.mx/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json';

if (file_exists($archivo)) {
    echo "OK: archivo existe y se puede leer";
} else {
    echo "ERROR: archivo no se puede acceder";
}

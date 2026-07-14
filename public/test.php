<?php
$path = WRITEPATH . 'firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json';
if (is_readable($path)) {
    echo "Archivo accesible";
} else {
    echo "No se puede leer";
}
?>
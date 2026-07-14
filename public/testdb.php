<?php
$host = 'localhost'; // o IP del servidor
$user = 'root';
$pass = '';
$db   = 'vvgdlsistema';
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error al conectar: " . $conn->connect_error);
} else {
    echo "Conexión exitosa desde PHP puro!";
}
?>

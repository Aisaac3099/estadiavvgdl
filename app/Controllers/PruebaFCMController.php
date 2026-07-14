<?php
namespace App\Controllers;

use App\Libraries\FCMService;

class PruebaFCMController extends \CodeIgniter\Controller
{
    public function index()
    {
        $fcm = new FCMService();

        echo "Ruta JSON usada: " . $fcm->serviceAccountPath . "<br>";

        $titulo = "Notificación de prueba";
        $cuerpo = "Mensaje enviado desde FCMService";
        $datos = ['tipo'=>'prueba','id'=>123];

        $res = $fcm->enviarNotificacion($titulo,$cuerpo,$datos);

        if($res){
            echo "Notificaciones enviadas: {$res['enviados']} de {$res['total']}";
        } else {
            echo "Error al enviar notificación. Revisa writable/logs/";
        }
    }
    // En cualquier controlador o en PruebaFCMController
public function diagnosticarRuta()
{
    echo "<h3>Diagnóstico de Rutas</h3>";
    
    // Rutas del sistema
    echo "APPPATH: " . APPPATH . "<br>";
    echo "ROOTPATH: " . ROOTPATH . "<br>"; 
    echo "FCPATH: " . FCPATH . "<br>";
    
    // Verificar si existe en diferentes ubicaciones
    $possibleFiles = [
        'sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json',
        ROOTPATH . 'app/Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json',
        APPPATH . 'Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json',
        FCPATH . '../app/Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json'
    ];
    
    foreach ($possibleFiles as $file) {
        $exists = file_exists($file) ? '✅ EXISTE' : '❌ NO EXISTE';
        echo "{$file} -> {$exists}<br>";
    }
}

public function verificarPermisos()
{
    $ruta = '/home1/videovig/sistema.videovigilanciagdl.com.mx/app/Firebase/sistemavvgdl-f3701-firebase-adminsdk-fbsvc-507e0bc878.json';
    
    echo "<h3>Verificación de Permisos</h3>";
    echo "Ruta: " . $ruta . "<br>";
    echo "Existe: " . (file_exists($ruta) ? '✅ SÍ' : '❌ NO') . "<br>";
    echo "Es legible: " . (is_readable($ruta) ? '✅ SÍ' : '❌ NO') . "<br>";
    echo "Permisos: " . substr(sprintf('%o', fileperms($ruta)), -4) . "<br>";
    echo "Propietario: " . fileowner($ruta) . "<br>";
    echo "Grupo: " . filegroup($ruta) . "<br>";
    
    // Verificar si podemos leer el contenido
    $contenido = file_get_contents($ruta);
    echo "Se puede leer contenido: " . ($contenido !== false ? '✅ SÍ' : '❌ NO') . "<br>";
    echo "Tamaño: " . filesize($ruta) . " bytes<br>";
}

}

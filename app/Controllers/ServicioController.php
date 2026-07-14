<?php namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ServicioModel;
use App\Models\EstatusServicioModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;
// Isaac Agrego(para notificaciones):
use App\Models\NotificacionModel;

class ServicioController extends Controller
{
    /** 
     * @var ClienteModel
     */
    protected $clienteModel;

    /**
     * @var ServicioModel
     */
    protected $servicioModel;

    /**
     * @var EstatusServicioModel
     */
    protected $estatusServicioModel;
    
    /**
     * @var UsuarioModel
     */
    protected $usuarioModel;

    //Isaac agrego (para notificaciones):
    /**
     * @var NotificacionModel
     */
    protected $notificacionModel;

    public function __construct()
    {
        // Cargar los modelos en el constructor para hacerlos disponibles en todos los métodos.
        $this->clienteModel = new ClienteModel();
        $this->servicioModel = new ServicioModel();
        // Nota: He capitalizado UsuarioModel para seguir las convenciones de PHP/CI
        $this->usuarioModel = new UsuarioModel(); 
        $this->estatusServicioModel = new EstatusServicioModel();
        //Isaac agrego (para notificaciones):
        $this->notificacionModel = new NotificacionModel();
    }

    // Muestra el formulario para registrar un nuevo servicio.
    public function showServicioForm(): string
    {
        $data = [
            'clientes' => $this->clienteModel->findAll(),
            'servicios' => $this->servicioModel->findAll(), 
            'estatus' => $this->estatusServicioModel->findAll(), 
        ];
        return view('servicio/servicioForm', $data); 
    }

    // Registra un nuevo servicio en la base de datos.
    public function registerServicio(): RedirectResponse
    { 
        

        $session = session();
        $usuario_registrante_id = $session->get('user_id'); 
        $usuario_registrante_nombre = $session->get('nombre');

        if (empty($usuario_registrante_id)) {
            return $this->response->redirect(site_url('login'));
        }
        
        $data = $this->request->getPost();
        $data['registrante'] = $usuario_registrante_nombre; 
        
        // ✅ CORRECCIÓN: Asignar fecha y hora actual al campo fechaRegistrante
        $data['fechaRegistrante'] = date('Y-m-d H:i:s');

        

        // Insertar los datos en la base de datos
        if ($this->servicioModel->insert($data)) {
        
            $servicio_id = $this->servicioModel->insertID();
            
            
            $cliente_id = $data['cliente'] ?? null;
            $cliente = $cliente_id ? $this->clienteModel->find($cliente_id) : null;
            $nombreCliente = $cliente['nombre'] ?? 'Cliente Desconocido';
            // Isaac Agrego (para notificaciones):
                $this->notificacionModel->insert([
                    'titulo' => 'Nuevo Servicio Registrado',
                    'mensaje' => 'Cliente:' . $nombreCliente . ' | Servicio: ' . ($data['servicio'] ?? 'Sin Descripcion') . 
                    ' | Registrado por: ' . $usuario_registrante_nombre,
                    'modulo' => 'servicios',
                    'referencia_id' => $servicio_id,
                    'tipo' => 'info',
                    'leida' => 0
                ]);
            
            $datosParaFCM = [
                'servicio_id' => $servicio_id,
                'servicio_detalle' => $data['servicio'] ?? 'N/A',
            ];
            
            try {
                $fcmService = new \App\Libraries\FCMService(); 
                
               
                
                $fcmService->enviarNotificacion(
                    "🚨 ¡NUEVO SERVICIO AGREGADO!",
                    "Cliente: {$nombreCliente} | Detalle: {$datosParaFCM['servicio_detalle']} | Registrado por: {$usuario_registrante_nombre}",
                    $datosParaFCM
                );
                
                
                
            } catch (\Exception $e) {
                
                return redirect()->to('/servicios')->with('warning', 'Servicio creado, pero falló la notificación: ' . $e->getMessage());
            }
            
            return redirect()->to('/servicios')->with('success', 'Servicio creado y notificado.'); 
        }

        return redirect()->back()->withInput()->with('error', 'Error al registrar el servicio. ' . print_r($this->servicioModel->errors(), true));
    }

    // Método para que el frontend envíe el token generado por Firebase
    public function guardarToken()
    {
        $requestData = $this->request->getJSON(true);
        
        $token = $requestData['fcm_token'] ?? null;
        $userId = $requestData['user_id'] ?? null;

        $requestData = $this->request->getJSON(true);
        $token = $requestData['fcm_token'] ?? null;
        $userId = $requestData['user_id'] ?? null;

        

        if (!$token || !$userId) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Faltan datos']);
        }

        try {
            $tokenModel = model('TokenModel');
            
            
            
            // DEBUG: Verificar tabla antes de guardar
            $tokensAntes = $tokenModel->findAll();
            
            
            // Guardar token
            
            $tokenModel->guardarToken($userId, $token);
            
            // DEBUG: Verificar si se guardó
            $tokenGuardado = $tokenModel->where('fcm_token', $token)->first();
            
            if ($tokenGuardado) {
                
            } else {
                log_message('error', "❌ Token NO se guardó en la BD");
            }
            
            // Contar tokens después
            $tokensDespues = $tokenModel->where('activo', 1)->findAll();
            
            
            // Listar todos los tokens para debug
            foreach ($tokensDespues as $index => $tok) {
                log_message('debug', "🔔 Token $index: User=" . $tok['user_id'] . ", Token=" . substr($tok['fcm_token'], 0, 20) . '...');
            }
            
            return $this->response->setJSON([
                'status' => 'success', 
                'total_tokens' => count($tokensDespues),
                'token_guardado' => $tokenGuardado ? true : false,
                'debug' => [
                    'tokens_antes' => count($tokensAntes),
                    'tokens_despues' => count($tokensDespues)
                ]
            ]);
            
        } catch (\Exception $e) {
            
            return $this->response->setJSON([
                'status' => 'error', 
                'msg' => 'Error en servidor: ' . $e->getMessage()
            ]);
        }
    }

    // Muestra el formulario para editar un servicio.
    public function editServicio(int $id): string | RedirectResponse
    {
        $servicio = $this->servicioModel->find($id);

        if ($servicio) {
            $data = [
                'clientes' => $this->clienteModel->findAll(),
                'estatusservicio' => $this->estatusServicioModel->findAll(),
                'servicio' => $servicio,
                //AGREGAR: información de estatus para la condición
                'estatus_actual' => $servicio['estatusServ'] // Este es el ID del estatus
            ];
            return view('servicio/editServicio', $data);
        }

        return redirect()->to('/servicios')->with('error', 'Servicio no encontrado.');
    }

    // Actualiza un registro de servicio en la base de datos.
    public function updateServicio(int $id): RedirectResponse
    {
        // Obtener todos los datos POST directamente desde el formulario.
        $data = $this->request->getPost();

        if ($this->servicioModel->update($id, $data)) {
            return redirect()->to('/servicios')->with('success', 'Servicio actualizado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al actualizar el servicio.');
    }

    // Muestra una lista de todos los servicios.
    public function listarServicios()
    {
        // Obtener servicios por cada estatus
        $data = [
            'serviciosPorAgendar' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'POR AGENDAR')
                ->findAll(),

            'serviciosAgendados' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'AGENDADO')
                ->findAll(),

            'serviciosEnProceso' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'EN PROCESO')
                ->findAll(),

            'serviciosTerminados' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'TERMINADO')
                ->findAll(),

            'serviciosDetenidos' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'DETENIDO')
                ->findAll(),

            'serviciosCancelados' => $this->servicioModel
                ->select('servicios.*, cliente.nombre as nombre_cliente')
                ->join('cliente', 'cliente.id = servicios.cliente')  
                ->join('estatusservicio', 'estatusservicio.id = servicios.estatusServ')
                ->where('estatusservicio.estatus', 'CANCELADO')
                ->findAll(),
        ];

        return view('servicio/listarServicios', $data);
    }

    // Elimina un registro de servicio de la base de datos.
    public function eliminar(int $id): RedirectResponse
    {
        if ($this->servicioModel->delete($id)) {
            return redirect()->to('/servicios')->with('success', 'Servicio eliminado con éxito.');
        }

        return redirect()->to('/servicios')->with('error', 'Error al eliminar el servicio.');
    }

    public function diagnosticoTokens()
    {
        $tokenModel = model('TokenModel');
        $tokens = $tokenModel->obtenerTokensActivos();
        
        $resultado = [
            'total_tokens' => count($tokens),
            'tokens_por_usuario' => [],
            'tokens_detalle' => []
        ];
        
        // Agrupar por usuario
        foreach ($tokens as $token) {
            $userId = $token['user_id'] ?? 'no_id';
            if (!isset($resultado['tokens_por_usuario'][$userId])) {
                $resultado['tokens_por_usuario'][$userId] = 0;
            }
            $resultado['tokens_por_usuario'][$userId]++;
            
            $resultado['tokens_detalle'][] = [
                'user_id' => $userId,
                'token_preview' => substr($token['fcm_token'], 0, 20) . '...',
                'token_completo' => $token['fcm_token']
            ];
        }
        
        
        return $this->response->setJSON($resultado);
    }
    
}
<?php namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ServicioModel;
use App\Models\TecnicoModel;
use App\Models\ServicioTecnicoModel;
use App\Models\AgendaModel;
use App\Models\EstatusServicioModel;
use App\Models\EstatusPagoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse; // Import RedirectResponse for explicit return types

class AgendaController extends Controller
{
    /**
     * @var AgendaModel
     */
    protected $agendaModel;

    /**
     * @var ClienteModel
     */
    protected $clienteModel;

    /**
     * @var ServicioModel
     */
    protected $servicioModel;

    /**
     * @var TecnicoModel
     */
    protected $tecnicoModel;
    
     /**
     * @var ServicioTecnicoModel
     */
    protected $ServicioTecnicoModel;

    /**
     * @var EstatusServicioModel
     */
    protected $estatusServicioModel;

    /**
     * @var EstatusPagoModel
     */
    protected $estatusPagoModel;

    

    /**
     * Constructor para cargar los modelos una sola vez.
     */
    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->clienteModel = new ClienteModel();
        $this->servicioModel = new ServicioModel();
        $this->tecnicoModel = new TecnicoModel();
        $this->ServicioTecnicoModel = new ServicioTecnicoModel();
        $this->estatusServicioModel = new EstatusServicioModel();
        $this->estatusPagoModel = new EstatusPagoModel();
    }

    // Método existente (mantener)
    public function showAgendaForm(): string
    {
        $data = [
            'clientes' => $this->clienteModel->findAll(),
            'servicios' => $this->servicioModel->findAll(),
            'tecnicos' => $this->tecnicoModel->findAll(),
        ];
        return view('agenda/agendaForm', $data);
    }

    // ✅ NUEVO MÉTODO para agendar desde servicio existente
    public function agendaFormDesdeServicio(int $servicio_id): string
    {
        // ✅ DEBUG TEMPORAL
        log_message('debug', '🔍 ID de servicio recibido: ' . $servicio_id);
        
        // Obtener datos del servicio
        $servicio = $this->servicioModel
            ->select('servicios.*, cliente.nombre as cliente_nombre, cliente.telefono, cliente.correo')
            ->join('cliente', 'cliente.id = servicios.cliente')
            ->where('servicios.id', $servicio_id)
            ->first();
        
        // ✅ DEBUG: Verificar si encontró el servicio
        if (!$servicio) {
            log_message('error', '❌ No se encontró servicio con ID: ' . $servicio_id);
            return redirect()->to('/servicios')->with('error', 'Servicio no encontrado.');
        } else {
            log_message('debug', '✅ Servicio encontrado: ' . $servicio['cliente_nombre']);
        }
        
        $servicioData = [
            'servicio_id' => $servicio_id,
            'servicio_descripcion' => $servicio['servicio'],
            'cliente_id' => $servicio['cliente'],
            'cliente_nombre' => $servicio['cliente_nombre'],
            'cliente_telefono' => $servicio['telefono'],
            'cliente_correo' => $servicio['correo'],
            'prioridad' => $servicio['prioridad'] ?? 'Media'
        ];
        
        $data = [
            'servicio' => $servicioData,
            'clientes' => $this->clienteModel->findAll(),
            'servicios' => $this->servicioModel->findAll(),
            'tecnicos' => $this->tecnicoModel->findAll(),
        ];
        
        // ✅ DEBUG: Verificar qué datos se están pasando
        log_message('debug', '📋 Datos del servicio para agenda: ' . print_r($servicioData, true));
        
        return view('agenda/agendaForm', $data);
    }
        

    public function createAgenda(): RedirectResponse
    {
         // ✅ DEBUG TEMPORAL: Verificar qué datos llegan
        $postData = $this->request->getPost();
        log_message('debug', '📨 Datos recibidos en createAgenda: ' . print_r($postData, true));
        
        $servicio_id = $postData['servicio_id'] ?? null;
        log_message('debug', '🔍 servicio_id recibido: ' . $servicio_id);
        
        // 1. Obtener todos los datos del formulario
        $postData = $this->request->getPost();
        
        // ✅ NUEVO: Verificar si viene de un servicio existente
        $servicio_id = $postData['servicio_id'] ?? null;
        
        // 2. Separar los datos de técnicos del resto del formulario
        $tecnicosIds = $postData['tecnicos'] ?? [];
        unset($postData['tecnicos']);

        $session = session();
        $usuario_id = $session->get('nombre');

        if (empty($usuario_id)) {
            return $this->response->redirect(site_url('login'));
        }
        
        $postData['registrante'] = $usuario_id;  
        
        // 3. Insertar los datos de la agenda primero.
        if ($this->agendaModel->insert($postData)) {
            // 4. Obtener el ID de la agenda recién creada
            $agendaId = $this->agendaModel->getInsertID();

            // ✅ NUEVO: Si viene de un servicio existente, actualizar su estatus a "AGENDADO" (ID 1)
            if ($servicio_id) {
                $this->servicioModel->update($servicio_id, [
                    'estatusServ' => 1 // ID de "AGENDADO"
                ]);
                log_message('debug', '✅ Servicio ' . $servicio_id . ' actualizado a AGENDADO');
            }
            
            // 5. Insertar las asignaciones en la tabla pivote si hay técnicos seleccionados
            if (!empty($tecnicosIds)) { 
                $asociaciones = [];
                foreach ($tecnicosIds as $tecnicoId) { 
                    $asociaciones[] = [
                        'agenda_id' => $agendaId,
                        'tecnico_id' => $tecnicoId,
                    ];
                } 
                $this->ServicioTecnicoModel->insertBatch($asociaciones);
            } 
            
            return redirect()->to('/agenda')->with('success', 'Agenda actualizada con éxito.');
        }

        // ✅ MOSTRAR ERRORES DETALLADOS
        $errors = $this->agendaModel->errors();
        log_message('error', '❌ Error al insertar agenda: ' . print_r($errors, true));
        
        return redirect()->back()
            ->with('error', 'Error al registrar la agenda. Errores: ' . print_r($errors, true))
            ->withInput();
    }  

    public function editAgenda(int $id): string | RedirectResponse
    {
        $agenda = $this->agendaModel->find($id);

        if ($agenda) {
            // **NUEVA CONSULTA:** Obtener los IDs de los técnicos asignados a esta agenda
            $tecnicosAsignados = $this->ServicioTecnicoModel
                                        ->where('agenda_id', $id)
                                        ->select('tecnico_id')
                                        ->findAll();
            
            // Convertir el resultado a un array simple de IDs
            $tecnicosSeleccionados = array_column($tecnicosAsignados, 'tecnico_id');

            // Obtener el servicio completo con su estatus 30/10/2025
            //$servicio = $this->servicioModel->find($agenda['servicio']);

            $data = [
                'clientes' => $this->clienteModel->findAll(),
                'tecnicos' => $this->tecnicoModel->findAll(),
                'estatusservicio' => $this->estatusServicioModel->findAll(),
                'agenda' => $agenda,
                'servicio' => $this->servicioModel->find($agenda['servicio']),
                'estatusPago' => $this->estatusPagoModel->findAll(),
                // Pasar el array de IDs a la vista
                'tecnicosSeleccionados' => $tecnicosSeleccionados
            ];
            
            return view('agenda/editAgenda', $data);
        }
        return redirect()->to('/agenda')->with('error', 'Agenda no encontrada.');
    }

     // Método para actualizar una agenda existente
        public function updateAgenda(int $id): RedirectResponse
        {
            // 1. Primero obtener la agenda existente para tener los datos actuales
            $agendaExistente = $this->agendaModel->find($id);
            if (!$agendaExistente) {
                return redirect()->to('/agenda')->with('error', 'Agenda no encontrada.');
            }

            // 2. Obtener todos los datos del formulario
            $postData = $this->request->getPost();

            // 3. Separar los datos de técnicos y el estado del servicio
            $tecnicosIds = $postData['tecnicos'] ?? [];
            $estatusServicio = $postData['estatusServicio'] ?? null;
            unset($postData['tecnicos']);
            unset($postData['estatusServicio']);

            // 4. Actualizar los datos principales de la agenda
            if ($this->agendaModel->update($id, $postData)) {
                
                // 5. Actualizar el estado del servicio en la tabla servicios
                if ($estatusServicio) {
                    // Usar el servicio_id de la agenda existente
                    $servicioId = $agendaExistente['servicio'];
                    
                    $this->servicioModel->update($servicioId, [
                        'estatusServ' => $estatusServicio
                    ]);
                }

                // 6. Eliminar las asignaciones de técnicos antiguas
                $this->ServicioTecnicoModel->where('agenda_id', $id)->delete();

                // 7. Insertar las nuevas asignaciones en la tabla pivote
                if (!empty($tecnicosIds)) {
                    $asociaciones = [];
                    foreach ($tecnicosIds as $tecnicoId) {
                        $asociaciones[] = [
                            'agenda_id' => $id,
                            'tecnico_id' => $tecnicoId,
                        ];
                    }
                    $this->ServicioTecnicoModel->insertBatch($asociaciones);
                }

                return redirect()->to('/agenda')->with('success', 'Agenda actualizada con éxito.');
            }

            return redirect()->back()->with('error', 'Error al actualizar la agenda.');
        }    

    public function listarAgenda(): string
    {
        $agenda = $this->agendaModel
            ->select('agenda.*, cliente.nombre as nombre_cliente, servicios.servicio as servicio_descripcion, estatuspago.estatus, estatuspago.etiquetas')
            ->join('cliente', 'cliente.id = agenda.cliente') 
            ->join('servicios', 'servicios.id = agenda.servicio')
            ->join('estatuspago', 'agenda.estatusPago = estatuspago.id', 'left') // <- LEFT JOIN
            ->where('servicios.estatusServ <>', 3) //21/10/2025 agregado para no mostrar los servicios terminados
            ->orderBy('fechaInicio', 'ASC') // <- 30/09/2025 ordenar por fecha 

            ->findAll();

            
        
        $asignaciones = $this->ServicioTecnicoModel
        ->select('servicio_tecnicos.*, tecnicos.nombre as nombre_tecnico')
        ->join('tecnicos', 'tecnicos.id = servicio_tecnicos.tecnico_id')
        ->findAll();
        

        // Organizar los técnicos por agenda_id
        $tecnicosPorAgenda = [];
        foreach ($asignaciones as $asignacion) {
            $agendaId = $asignacion['agenda_id'];
            if (!isset($tecnicosPorAgenda[$agendaId])) {
                $tecnicosPorAgenda[$agendaId] = [];
            }
            $tecnicosPorAgenda[$agendaId][] = $asignacion['nombre_tecnico'];
        }

        // Unir la información de técnicos a cada registro de agenda
        foreach ($agenda as &$item) {
            $item['nombres_tecnicos'] = $tecnicosPorAgenda[$item['id']] ?? ['Sin asignar'];
        }

        $data['agenda'] = $agenda;
        
        return view('agenda/listarAgenda', $data);
    }

    public function eliminar(int $id): RedirectResponse
    {
        if ($this->agendaModel->delete($id)) {
            return redirect()->to('/agenda')->with('success', 'Agenda eliminada con éxito.');
        }
        return redirect()->to('/agenda')->with('error', 'Error al eliminar la agenda.');
    }

    public function showCalendar(): string
    {
        return view('agenda/calendario');
    }

    public function getEventos(): object
    {
        // 1. Obtener la lista de agendas
        $eventos = $this->agendaModel
            ->select('agenda.*, cliente.nombre as nombre_cliente, servicios.servicio as descripcion_servicio, servicios.material as serv_material')
            ->join('cliente', 'cliente.id = agenda.cliente')
            ->join('servicios', 'servicios.id = agenda.servicio')
            ->findAll();
        // 2. Obtener todas las asignaciones de técnicos para las agendas
        $asignaciones = $this->ServicioTecnicoModel
            ->select('servicio_tecnicos.*, tecnicos.nombre as nombre_tecnico')
            ->join('tecnicos', 'tecnicos.id = servicio_tecnicos.tecnico_id')
            ->findAll();

        // 3. Organizar los técnicos por agenda_id
        $tecnicosPorAgenda = [];
        foreach ($asignaciones as $asignacion) {
            $agendaId = $asignacion['agenda_id'];
            if (!isset($tecnicosPorAgenda[$agendaId])) {
                $tecnicosPorAgenda[$agendaId] = [];
            }
            $tecnicosPorAgenda[$agendaId][] = $asignacion['nombre_tecnico'];
        }

        // 4. Formatear los datos para FullCalendar
        $eventosFullCalendar = [];
        foreach ($eventos as $evento) {
            // Unir la información de técnicos a cada evento
            $nombresTecnicos = $tecnicosPorAgenda[$evento['id']] ?? ['Sin asignar'];
            $tecnicosFormateados = implode(', ', $nombresTecnicos);

            $eventosFullCalendar[] = [
                'title' => $evento['nombre_cliente'] . ' - Servicio: ' . $evento['descripcion_servicio'] . ' - Téc. ' . $tecnicosFormateados,
                'start' => $evento['fechaInicio'],
                'extendedProps' => [
                    'servicio' => $evento['descripcion_servicio'],
                    'material' => $evento['serv_material'],
                    'tecnicos' => $tecnicosFormateados // Opcional: pasar los técnicos en las propiedades extendidas
                ],
                'url' => base_url('editAgenda/' . $evento['id'])
            ];
        }
        return $this->response->setJSON($eventosFullCalendar);
        
    }

    public function getServiciosPorCliente($clienteId)
    {  
        // Obtener los servicios de la base de datos relacionados con el cliente
        // Suponiendo que tienes un campo 'cliente_id' en tu tabla de servicios
        $servicios = $this->servicioModel->where('cliente', $clienteId)->findAll();

        // Devolver la respuesta como JSON
        return $this->response->setJSON($servicios);
    }

    // Método para procesar el agendamiento desde servicio existente
    public function createAgendaDesdeServicio(): RedirectResponse
    {
        return $this->createAgenda(); // Reutiliza el mismo método
    }
}
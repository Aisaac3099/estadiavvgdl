<?php namespace App\Controllers;

use App\Models\AutoModel;
use App\Models\ServicioAutoModel;
use App\Models\ServicioAutoEvidenciaModel;

class ServicioAutoController extends BaseController
{
    protected $servicioModel;
    protected $autoModel;
    protected $evidenciaModel;

    private array $tiposRegistroPermitidos = ['unico', 'periodico', 'por_asignar'];
    private array $estadosServicioPermitidos = ['material_comprado', 'en_proceso', 'realizado', 'cancelado'];

    public function __construct()
    {
        $this->servicioModel = new ServicioAutoModel();
        $this->autoModel = new AutoModel();
        $this->evidenciaModel = new ServicioAutoEvidenciaModel();
    }

    public function index()
    {
        $data ['autos'] = $this->autoModel->where('activo', 1)->findAll();
        $data ['servicios'] = $this->servicioModel->select(
            'servicios_autos.*,
            autos.alias, 
            autos.placas,
            autos.marca,
            autos.modelo'
        )
        ->join('autos', 'autos.id = servicios_autos.auto_id')
        ->findAll();

        $totalServicios = count($data['servicios']);
        $vencidos = 0;
        $porVencer = 0;
        $vigentes = 0;
        $noAplica =0;
        
        foreach ($data ['servicios']as $servicio){
            if (empty($servicio['proximo_servicio'])){
                $noAplica++;
            }else{
                $hoy = date('Y-m-d');
                $diasRestantes = floor((strtotime($servicio['proximo_servicio'])-strtotime($hoy))/86400);
                if ($diasRestantes <0){
                    $vencidos++;
                }elseif($diasRestantes <=30){
                    $porVencer++;
                }else{
                    $vigentes++;
                }
            }
        }
        $data['totalServicios'] = $totalServicios;
        $data['vencidos'] = $vencidos;
        $data['porVencer'] = $porVencer;
        $data['vigentes'] = $vigentes;
        $data['noAplica'] = $noAplica;

        return view ('servicios_autos/index', $data);
    }

    public function store()
    {
        $tipoRegistro = $this->request->getPost('tipo_registro');
        $validacion = $this->validarDatosServicio($tipoRegistro);
        if ($validacion !== true) {
            return $validacion;
        }

        $datosServicio = $this->prepararDatosServicio($tipoRegistro);

        if(!$this->servicioModel->insert($datosServicio)){
            return redirect()->back()->with('error', 'No se pudo registrar el servicio')->withInput();
        }
        $servicioId= $this->servicioModel->getInsertID();
        $this->guardarEvidencias($servicioId);

        if ($this->request->getPost('origen')==='programar'){
            return redirect()->to(base_url('autos'))->with('success', 'Servicio Programado correctamente');
        }
        return redirect()->back()->with('success', 'Servicio Registrado correctamente');
    }

    public function edit($id)
    {
        $data['servicio'] = $this->servicioModel->find($id);
        if(!$data['servicio']){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['esPendiente'] = ($data['servicio']['tipo_registro']=='por_asignar');
        $data['evidencias'] = $this->evidenciaModel->where('servicio_auto_id', $id)->findAll();
        return view('servicios_autos/edit', $data);
    }

    public function update($id)
    {
        $servicio = $this->servicioModel->find($id);
        if (!$servicio) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tipoRegistro = $this->request->getPost('tipo_registro');
        $validacion = $this->validarDatosServicio($tipoRegistro, $id);
        if ($validacion !== true) {
            return $validacion;
        }

        $datosServicio = $this->prepararDatosServicio($tipoRegistro, $servicio);

        if (!$this->servicioModel->update($id, $datosServicio)) {
            return redirect()->back()->with('error', 'No se pudo actualizar el servicio. Revise los datos e intente nuevamente.')->withInput();
        }

        $this->guardarEvidencias($id);

        return redirect()->to(base_url('servicios_autos'))->with('success', 'Servicio Actualizado Exitosamente');
    }

    public function programar ($auto_id){
        $auto = $this->autoModel->find($auto_id);
        if (!$auto){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['auto']=$auto;
        return view('servicios_autos/programar', $data);
    }

    public function detalles ($id)
    {
        $servicio = $this->servicioModel->find($id);
        if(!$servicio){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $auto = $this->autoModel->find($servicio['auto_id']);
        $evidencias = $this->evidenciaModel->where('servicio_auto_id', $id)->findAll();

        $data = [
            'servicio' => $servicio,
            'auto' => $auto,
            'evidencias' =>$evidencias
        ];

        return view('servicios_autos/detalles', $data);
    }

    public function eliminarEvidencia($id)
    {
        $evidencia = $this->evidenciaModel->find($id);
        if($evidencia){
            $ruta = FCPATH . 'public/uploads/servicios_autos/' . $evidencia['archivo'];
            if(file_exists($ruta)){
                unlink($ruta);
            }
            $this->evidenciaModel->delete($id);
        }
        return redirect()->back()->with('success', 'Evidencia eliminada correctamente');
    }

    private function validarDatosServicio($tipoRegistro, $id = null)
    {
        if (!in_array($tipoRegistro, $this->tiposRegistroPermitidos, true)) {
            return redirect()->back()->with('error', 'Seleccione un tipo de registro válido.')->withInput();
        }

        $autoId = $this->request->getPost('auto_id');
        if ($autoId !== null && $autoId !== '' && !$this->autoModel->find($autoId)) {
            return redirect()->back()->with('error', 'Seleccione un auto válido.')->withInput();
        }

        if ($id === null && (empty($autoId) || !$this->autoModel->find($autoId))) {
            return redirect()->back()->with('error', 'Seleccione un auto válido.')->withInput();
        }

        if ($tipoRegistro === 'por_asignar' && empty($this->request->getPost('proximo_servicio'))) {
            return redirect()->back()->with('error', 'Debe seleccionar una fecha para programar el servicio.')->withInput();
        }

        if ($tipoRegistro !== 'por_asignar') {
            $estadoServicio = $this->request->getPost('estado_servicio');
            if (empty($estadoServicio) || !in_array($estadoServicio, $this->estadosServicioPermitidos, true)) {
                return redirect()->back()->with('error', 'Seleccione un estado del servicio válido.')->withInput();
            }
        }

        return true;
    }

    private function prepararDatosServicio($tipoRegistro, array $servicioActual = null): array
    {
        $categoriaServicio = null;
        $tipoServicio = null;

        if ($tipoRegistro === 'periodico') {
            $categoriaServicio = $this->request->getPost('categoria_servicio');
            $tipoServicio = $this->request->getPost('tipo_servicio_select') ?: $this->request->getPost('tipo_servicio');
            if ($tipoServicio === 'Otro') {
                $tipoServicio = $this->request->getPost('tipo_servicio_otro');
            }
        } elseif ($tipoRegistro === 'por_asignar') {
            $tipoServicio = $this->request->getPost('tipo_servicio') ?: $this->request->getPost('tipo_servicio_texto');
        } else {
            $tipoServicio = $this->request->getPost('tipo_servicio_texto') ?: $this->request->getPost('tipo_servicio');
        }

        $proximoServicio = $this->request->getPost('proximo_servicio');
        if ($tipoRegistro === 'unico' || empty($proximoServicio)) {
            $proximoServicio = null;
        }

        $fechaServicio = $this->request->getPost('fecha_servicio');
        if(empty($fechaServicio)){
            $fechaServicio = date('Y-m-d');
        }

        $estadoServicio = $this->request->getPost('estado_servicio');
        $observacionEstado = $this->request->getPost('observacion_estado');
        if ($tipoRegistro === 'por_asignar'){
            $estadoServicio = null;
            $observacionEstado = null;
        } elseif ($observacionEstado === '') {
            $observacionEstado = null;
        }

        $data = [
            'fecha_servicio' => $fechaServicio,
            'tipo_registro' => $tipoRegistro,
            'estado_servicio' => $estadoServicio,
            'observacion_estado' => $observacionEstado,
            'categoria_servicio' => $categoriaServicio,
            'tipo_servicio' => $tipoServicio,
            'descripcion' => $this->request->getPost('descripcion'),
            'proximo_servicio' => $proximoServicio,
        ];

        $autoId = $this->request->getPost('auto_id');
        if ($autoId !== null && $autoId !== '') {
            $data['auto_id'] = $autoId;
        } elseif ($servicioActual && isset($servicioActual['auto_id'])) {
            $data['auto_id'] = $servicioActual['auto_id'];
        }

        return $data;
    }

    private function guardarEvidencias($servicioId): void
    {
        $evidencias = $this->request->getFiles();
        if(isset($evidencias['evidencias'])){
            foreach ($evidencias['evidencias'] as $evidencia){
                if ($evidencia->isValid() && ! $evidencia->hasMoved()){
                    $nombreArchivo = $evidencia->getRandomName();
                    $evidencia->move(
                        FCPATH. 'public/uploads/servicios_autos/',
                        $nombreArchivo
                    );
                    $this->evidenciaModel->insert([
                        'servicio_auto_id' => $servicioId, 'archivo' =>$nombreArchivo
                    ]);
                }
            }
        }
    }
}

<?php namespace App\Controllers;

use App\Models\AutoModel;
use App\Models\AutoImagenModel;

class AutoController extends BaseController
{
    protected $autoModel;
    protected $autoImagenModel;

    public function __construct()//----------------------------------------------------------------------------
    {
        $this->autoModel = new AutoModel();
        $this->autoImagenModel = new AutoImagenModel();
    }

    public function store ()//----------------------------------------------------------------------------
    {
        $data = [

            'placas' => $this->request->getPost('placas'),
            'marca' => $this->request->getPost('marca'),
            'modelo' => $this->request->getPost('modelo'),
            'activo' => 1,
            'alias' => $this->request->getPost('alias')
        ];

        $this->autoModel->insert($data);
        $autoId = $this->autoModel->getInsertID();
        $imagenes = $this->request->getFiles();
                if(isset($imagenes['imagenes']))
                    {
                        foreach($imagenes['imagenes'] as $imagen)
                            {
                                if ($imagen->isValid() && !$imagen->hasMoved())
                                    {
                                        $nombreImagen = $imagen->getRandomName();
                                        $imagen->move(
                                            FCPATH . 'public/uploads/autos/' ,
                                            $nombreImagen
                                        );
                                        $this->autoImagenModel->insert(['auto_id' => $autoId, 'imagen'=> $nombreImagen
                                        ]);
                                    }
                            }
                    }
        return redirect()->back()->with('success', 'Auto Registrado Correctamente');
    }

    public function index()//----------------------------------------------------------------------------
    {
        $autoImagenModel = new AutoImagenModel();
        $data ['autos'] = $this->autoModel->orderBy('activo', 'DESC')->orderBy('alias', 'ASC')->findAll();

        foreach ($data['autos'] as &$auto) {
            $auto['imagenes'] = $autoImagenModel->where('auto_id', $auto['id'])->findAll();
        }
        return view('autos/index', $data);
    }

    public function baja($id)//----------------------------------------------------------------------------
    {
        $this->autoModel->update($id,[
            'activo' => 0
        ]);

        return redirect()->back()->with('success', 'Auto dado de baja correctamente');
    }

     public function reactivar($id)//----------------------------------------------------------------------------
    {
        $this->autoModel->update($id,[
            'activo' => 1
        ]);

        return redirect()->back()->with('success', 'Auto Reactivado correctamente');
    }

    public function edit($id)//----------------------------------------------------------------------------
        {
           $data['auto'] = $this->autoModel->find($id);
           $data['imagenes'] = $this->autoImagenModel->where('auto_id' , $id)->findAll();

            return view('autos/edit', $data);
        }

    public function update($id)//----------------------------------------------------------------------------
    {
        $data = [
            
            'placas' => $this->request->getPost('placas'),
            'marca' => $this->request->getPost('marca'),
            'modelo' => $this->request->getPost('modelo'),
            'alias' => $this->request->getPost('alias')
        ];

        $this->autoModel->update($id, $data);
        $autoId = $id;
        $imagenes = $this->request->getFiles();
                if(isset($imagenes['imagenes']))
                    {
                        foreach($imagenes['imagenes'] as $imagen)
                            {
                                if ($imagen->isValid() && !$imagen->hasMoved())
                                    {
                                        $nombreImagen = $imagen->getRandomName();
                                        $imagen->move(
                                            FCPATH . 'public/uploads/autos/' ,
                                            $nombreImagen
                                        );
                                        $this->autoImagenModel->insert(['auto_id' => $autoId, 'imagen'=> $nombreImagen
                                        ]);
                                    }
                            }
                    }

        return redirect()->to(base_url('autos'))->with('success', 'Auto Actualizado Exitosamenteamente');
    }

    public function eliminarImagen($id)//--------------------------------------------------------------------------
    {
        $imagen = $this->autoImagenModel->find($id);
        if($imagen)
            {
                $ruta = FCPATH . 'public/uploads/autos/' . $imagen['imagen'];
                

                if (file_exists($ruta))
                    {
                        unlink($ruta);
                    }
                    $this->autoImagenModel->delete($id);
            }
            return redirect()->back();
    }

    public function detalles($id)//--------------------------------------------------------------------------
    {
        $data['auto'] = $this->autoModel->find($id);
        if (!$data['auto']){
            throw \codeIgniter\Exceptions\PageNotFoundException::forPageNotFound();// en caso de no encontrar, manda "pagina no encontrada"
        }
        $data['imagenes'] = $this->autoImagenModel->where('auto_id', $id)->findAll();
        $servicioModel = new \App\Models\ServicioAutoModel();
        $data['servicios'] = $servicioModel->where('auto_id', $id)->orderBy('fecha_servicio', 'DESC')->findAll();
        $data['serviciosPendientes']= $servicioModel->where('auto_id', $id)->where('tipo_registro', 'por_asignar')->findAll();
        $data['historialServicios']= $servicioModel->where('auto_id', $id)->where('tipo_registro !=', 'por_asignar')->orderBy('fecha_servicio', 'DESC')->findAll();

        return view('autos/detalles', $data);
    }


}


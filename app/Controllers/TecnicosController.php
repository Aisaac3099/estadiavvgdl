<?php namespace App\Controllers;

use App\Models\TecnicoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class TecnicosController extends Controller
{
    /**
     * @var tecnicoModel
     */
    protected $tecnicoModel;

    public function __construct()
    {
        // Carga el modelo una sola vez para todo el controlador
        $this->tecnicoModel = new tecnicoModel();
    }

    // Muestra el formulario para registrar un nuevo técnico
    public function showTecnicoForm(): string
    {
        return view('tecnicos/agregar');
    }

    // Registra un nuevo técnico en la base de datos
    public function registerTecnico(): RedirectResponse
    {
        $data = $this->request->getPost();

        if ($this->tecnicoModel->insert($data)) {
            return redirect()->to('/tecnicos')->with('success', 'Técnico registrado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al registrar el técnico.');
    }

    // Muestra el formulario para editar un técnico
    public function editTecnico(int $id): string | RedirectResponse
    {
        $tecnico = $this->tecnicoModel->find($id);

        if ($tecnico) {
            return view('tecnicos/editar', ['tecnico' => $tecnico]);
        }

        return redirect()->to('/tecnicos')->with('error', 'Técnico no encontrado.');
    }

    // Actualiza un registro de técnico en la base de datos
    public function updateTecnico(int $id): RedirectResponse
    {
        $data = $this->request->getPost();

        if ($this->tecnicoModel->update($id, $data)) {
            return redirect()->to('/tecnicos')->with('success', 'Técnico actualizado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al actualizar el técnico.');
    }

    // Muestra una lista de todos los técnicos
    public function listarTecnicos(): string
    {
        $data['tecnicos'] = $this->tecnicoModel->findAll();
        return view('tecnicos/index', $data);
    }

    // Elimina un registro de técnico de la base de datos
    public function eliminar(int $id): RedirectResponse
    {
        if ($this->tecnicoModel->delete($id)) {
            return redirect()->to('/tecnicos')->with('success', 'Técnico eliminado con éxito.');
        }

        return redirect()->to('/tecnicos')->with('error', 'Error al eliminar el técnico.');
    }
}

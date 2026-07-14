<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class UsuariosController extends Controller
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    // Listar solo usuarios activos
    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->where('estatus', 1)->findAll();
        return view('usuarios/index', $data);
    }

    // Formulario de creación
    public function create()
    {
        return view('usuarios/create');
    }

    // Guardar nuevo usuario
    public function store()
    {
        $data = $this->request->getPost();

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['estatus']  = 1; // activo por defecto

        if ($this->usuarioModel->insert($data)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario creado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al crear el usuario.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if ($usuario) {
            return view('usuarios/edit', ['usuario' => $usuario]);
        }

        return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
    }

    // Actualizar usuario
    public function update($id)
    {
        $data = $this->request->getPost();

        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // si no se ingresa, no cambia
        }

        if ($this->usuarioModel->update($id, $data)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al actualizar el usuario.');
    }

    // Desactivar usuario (soft delete)
    public function delete($id)
    {
        if ($this->usuarioModel->update($id, ['estatus' => 0])) {
            return redirect()->to('/usuarios')->with('success', 'Usuario desactivado con éxito.');
        }

        return redirect()->to('/usuarios')->with('error', 'Error al desactivar el usuario.');
    }
}
?>
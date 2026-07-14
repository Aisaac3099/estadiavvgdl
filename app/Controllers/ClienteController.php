<?php namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class ClienteController extends Controller
{
    /**
     * @var ClienteModel
     */
    protected $clienteModel;

    public function __construct()
    {
        // Carga el modelo una sola vez para todo el controlador
        $this->clienteModel = new ClienteModel();
    }

    // Muestra el formulario para registrar un nuevo cliente
    public function showClienteForm(): string
    {
        return view('cliente/clienteForm');
    }

    // Registra un nuevo cliente en la base de datos
    public function registerCliente(): RedirectResponse
    {
        // Obtiene todos los campos del formulario POST en un solo array
        $data = $this->request->getPost();

        if ($this->clienteModel->insert($data)) {
            return redirect()->to('/clientes')->with('success', 'Cliente registrado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al registrar el cliente.');
    }

    // Muestra el formulario para editar un cliente
    public function editCliente(int $id): string | RedirectResponse
    {
        $cliente = $this->clienteModel->find($id);

        if ($cliente) {
            return view('cliente/editCliente', ['cliente' => $cliente]);
        }
        
        return redirect()->to('/clientes')->with('error', 'Cliente no encontrado.');
    }

    // Actualiza un registro de cliente en la base de datos
    public function updateCliente(int $id): RedirectResponse
    {
        // Obtiene todos los campos del formulario POST en un solo array
        $data = $this->request->getPost();

        if ($this->clienteModel->update($id, $data)) {
            return redirect()->to('/clientes')->with('success', 'Cliente actualizado con éxito.');
        }

        return redirect()->back()->with('error', 'Error al actualizar el cliente.');
    }

    // Muestra una lista de todos los clientes
    public function listarClientes(): string
    {
        $data['clientes'] = $this->clienteModel->findAll();
        return view('cliente/listarClientes', $data);
    }

    // Elimina un registro de cliente de la base de datos
    public function eliminar(int $id): RedirectResponse
    {
        if ($this->clienteModel->delete($id)) {
            return redirect()->to('/clientes')->with('success', 'Cliente eliminado con éxito.');
        }

        return redirect()->to('/clientes')->with('error', 'Error al eliminar el cliente.');
    }
}
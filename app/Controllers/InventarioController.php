<?php namespace App\Controllers;

use App\Models\InventarioFotoModel;
use App\Models\InventarioModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class InventarioController extends BaseController
{
    protected $inventarioModel;
    protected $fotoModel;
    protected $uploadPath;

    public function __construct()
    {
        $this->inventarioModel = new InventarioModel();
        $this->fotoModel = new InventarioFotoModel();
        $this->uploadPath = FCPATH . 'public/uploads/inventario/';
    }

    public function index()
    {
        $inventario = $this->inventarioModel
            ->orderBy('activo', 'DESC')
            ->orderBy('nombre', 'ASC')
            ->findAll();

        $fotosPorInventario = [];
        if (! empty($inventario)) {
            $ids = array_column($inventario, 'id');
            $fotos = $this->fotoModel->whereIn('inventario_id', $ids)->orderBy('id', 'ASC')->findAll();
            foreach ($fotos as $foto) {
                $fotosPorInventario[$foto['inventario_id']][] = $foto;
            }
        }

        foreach ($inventario as &$item) {
            $item['fotos'] = $fotosPorInventario[$item['id']] ?? [];
        }

        return view('inventario/index', ['inventario' => $inventario]);
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = $this->requestData();
        $data['activo'] = 1;

        if (! $this->inventarioModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'No fue posible registrar el elemento de inventario');
        }

        $this->guardarFotos((int) $this->inventarioModel->getInsertID());

        return redirect()->to(base_url('inventario'))->with('success', 'Elemento registrado correctamente');
    }

    public function edit($id)
    {
        $item = $this->buscarInventario($id);
        $fotos = $this->fotoModel->where('inventario_id', $id)->orderBy('id', 'ASC')->findAll();

        return view('inventario/edit', ['item' => $item, 'fotos' => $fotos]);
    }

    public function update($id)
    {
        $this->buscarInventario($id);

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        if (! $this->inventarioModel->update($id, $this->requestData())) {
            return redirect()->back()->withInput()->with('error', 'No fue posible actualizar el elemento de inventario');
        }

        $this->guardarFotos((int) $id);

        return redirect()->to(base_url('inventario'))->with('success', 'Elemento actualizado correctamente');
    }

    public function detalles($id)
    {
        $item = $this->buscarInventario($id);
        $fotos = $this->fotoModel->where('inventario_id', $id)->orderBy('id', 'ASC')->findAll();

        return view('inventario/detalles', ['item' => $item, 'fotos' => $fotos]);
    }

    public function baja($id)
    {
        $this->buscarInventario($id);
        $this->inventarioModel->update($id, ['activo' => 0]);

        return redirect()->back()->with('success', 'Elemento dado de baja correctamente');
    }

    public function reactivar($id)
    {
        $this->buscarInventario($id);
        $this->inventarioModel->update($id, ['activo' => 1]);

        return redirect()->back()->with('success', 'Elemento reactivado correctamente');
    }

    public function eliminarFoto($id)
    {
        $foto = $this->fotoModel->find($id);
        if (! $foto) {
            return redirect()->back()->with('error', 'La fotografía no existe');
        }

        $ruta = $this->uploadPath . $foto['foto'];
        if (is_file($ruta)) {
            unlink($ruta);
        }
        $this->fotoModel->delete($id);

        return redirect()->back()->with('success', 'Fotografía eliminada correctamente');
    }

    private function buscarInventario($id): array
    {
        $item = $this->inventarioModel->find($id);
        if (! $item) {
            throw PageNotFoundException::forPageNotFound('Elemento de inventario no encontrado');
        }
        return $item;
    }

    private function requestData(): array
    {
        return [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'alias' => $this->nullablePost('alias'),
            'marca' => $this->nullablePost('marca'),
            'modelo' => $this->nullablePost('modelo'),
            'cantidad' => (int) $this->request->getPost('cantidad'),
            'bodega' => (int) $this->request->getPost('bodega'),
            'anaquel' => (int) $this->request->getPost('anaquel'),
            'nivel' => (int) $this->request->getPost('nivel'),
        ];
    }

    private function nullablePost(string $field): ?string
    {
        $value = trim((string) $this->request->getPost($field));
        return $value === '' ? null : $value;
    }

    private function rules(): array
    {
        return [
            'nombre' => 'required|max_length[150]',
            'cantidad' => 'required|integer|greater_than_equal_to[0]',
            'bodega' => 'required|integer|greater_than_equal_to[1]',
            'anaquel' => 'required|integer|greater_than_equal_to[1]',
            'nivel' => 'required|integer|greater_than_equal_to[1]',
        ];
    }

    private function guardarFotos(int $inventarioId): void
    {
        if (! is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }

        $archivos = $this->request->getFiles();
        if (! isset($archivos['fotos'])) {
            return;
        }

        foreach ($archivos['fotos'] as $foto) {
            if (! $foto->isValid() || $foto->hasMoved()) {
                continue;
            }

            if (! in_array(strtolower($foto->getClientExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $nombreFoto = $foto->getRandomName();
            $foto->move($this->uploadPath, $nombreFoto);
            $this->fotoModel->insert(['inventario_id' => $inventarioId, 'foto' => $nombreFoto]);
        }
    }
}

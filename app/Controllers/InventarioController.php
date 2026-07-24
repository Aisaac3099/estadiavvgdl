<?php namespace App\Controllers;

use App\Models\InventarioFotoModel;
use App\Models\InventarioModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class InventarioController extends BaseController
{
    /**
     * Modelo encargado de consultar, registrar y actualizar
     * los elementos almacenados en el inventario.
     *
     * @var InventarioModel
     */
    protected $inventarioModel;

    /**
     * Modelo encargado de administrar las fotografías
     * relacionadas con cada elemento del inventario.
     *
     * @var InventarioFotoModel
     */
    protected $fotoModel;

    /**
     * Ruta física donde se almacenan las fotografías
     * pertenecientes al módulo de Inventario.
     *
     * @var string
     */
    protected $uploadPath;

    /**
     * Inicializa los modelos utilizados por el controlador
     * y define la carpeta donde se guardan las fotografías.
     */
    public function __construct()
    {
        $this->inventarioModel = new InventarioModel();
        $this->fotoModel = new InventarioFotoModel();
        $this->uploadPath = FCPATH . 'public/uploads/inventario/';
    }

    /**
     * Muestra el listado general del inventario
     * 
     * Obtiene todos los elementos, colocando primero los archivos,
     * relaciona sus fotografias y envia la informacion a la vista principal del modulo
     * 
     */
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

    /**
     * registra un nuevo elemento dentro de inventario,
     * valida los datos enviados por el formulario, guarda el elemento como activo y posteriormente
     * almacena las fotografias seleccionadas
     * 
     * si la validacion o el registro fallas, regresa al formulario cconservando los datos ingresados
     * 
     * 
     */
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

    /**
     * Muestra el formulario para editar un elemento del inventario
     * 
     * busca un registro mediante su ID, obtiene sus fotografias actuales y 
     * consulta los demas modelos registrados para detectar posibles
     * coincidencias durante la edicion
     * 
     */
    public function edit($id)
    {
        $item = $this->buscarInventario($id);
        $fotos = $this->fotoModel->where('inventario_id', $id)->orderBy('id', 'ASC')->findAll();
        $modelosRegistrados = $this->inventarioModel->select('id, nombre, modelo, activo')
        ->where('id !=', $id)->findAll();

        return view('inventario/edit', ['item' => $item, 'fotos' => $fotos,
        'modelosRegistrados' => $modelosRegistrados]);
    }

    /**
     * Actualiza la informacion de un elemento del inventario
     * verifica que el elemento exista, valida los datos enviados,
     * actualiza el registro y agrega fotografias nuevas sin eliminar las que ya tenia guardadas
     * 
     */
    public function update($id)
    {
        $itemActual = $this->buscarInventario($id);

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        //conservar la cantidad y evitar cambios sin historial
        $data = $this->requestData();
        $data['cantidad'] = (int) $itemActual['cantidad'];

        if (! $this->inventarioModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'No fue posible actualizar el elemento de inventario');
        }

        $this->guardarFotos((int) $id);

        return redirect()->to(base_url('inventario'))->with('success', 'Elemento actualizado correctamente');
    }

    /**
     * Muestra la informacion completa de un elemento del inventario,
     * busca el registro mediante su id, obtine todas sus fotografias y envia los datos a la vista de detalles.
     * 
     */
    public function detalles($id)
    {
        $item = $this->buscarInventario($id);
        $fotos = $this->fotoModel->where('inventario_id', $id)->orderBy('id', 'ASC')->findAll();

        return view('inventario/detalles', ['item' => $item, 'fotos' => $fotos]);
    }

    /**
     * da de baja logica a un elemento del invetario,
     * cambia el campo activo a cero sin eliminar el registro, sus fotografias
     * ni la informacion relacionada
     * 
     * 
     */
    public function baja($id)
    {
        $this->buscarInventario($id);
        $this->inventarioModel->update($id, ['activo' => 0]);

        return redirect()->back()->with('success', 'Elemento dado de baja correctamente');
    }

    /**
     * reactiva un elemento del inventario que estaba dado de baja, 
     * cambia el campo activo nuevamente a 1, permitiendo que
     * el elemento continue utilizandose dentro del sistema
     */
    public function reactivar($id)
    {
        $this->buscarInventario($id);
        $this->inventarioModel->update($id, ['activo' => 1]);

        return redirect()->back()->with('success', 'Elemento reactivado correctamente');
    }

    /**
     * elimina una fotografia relacionada con un elemento del inventario,
     * comprueba que el registro de la fotografia exista,
     * elimina primero si el archivo fisico almacenado en el servidor, despues
     * elimina su registro de la base de datos,
     * si el archivo fisico existe pero no puede eliminarse, conserva el registro
     * en la base de datos para evitar inconsistencias
     * 
     */
    public function eliminarFoto($id)
    {
        $foto = $this->fotoModel->find($id);
        if (! $foto) {
            return redirect()->back()->with('error', 'La fotografía no existe');
        }

        $ruta = $this->uploadPath . $foto['foto'];
        
        if (is_file($ruta) && ! unlink($ruta)) {
            return redirect()->back()
            ->with('error', 'No fue posible eliminar el archivo fisico de la fotografia');
        }
        $this->fotoModel->delete($id);

        return redirect()->back()->with('success', 'Fotografía eliminada correctamente');
    }

    /**
     * busca un elemento del inventario mediante su ID , este metodo
     * se utiliza en difrentes acciones del controlador para comprobar que el elemento
     * solicitado realmente exista
     * 
     */
    private function buscarInventario($id): array
    {
        $item = $this->inventarioModel->find($id);
        if (! $item) {
            throw PageNotFoundException::forPageNotFound('Elemento de inventario no encontrado');
        }
        return $item;
    }

    /**
     * prepara los datos enviados desde el formulario de inventario,
     * obtiene los valores recibidos mediante POST, elimina espacios innecesarios y convierte los 
     * campos numericos al tipo entero,
     * los campos opcionales vacios e guardan como NULL,
     * 
     * este metodo se utiliza tanto al registrar como al actualizar un elemento del inventario
     * 
     */
    private function requestData(): array
    {
        return [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'alias' => $this->nullablePost('alias'),
            'marca' => $this->nullablePost('marca'),
            'modelo' => $this->nullablePost('modelo'),
            'descripcion' => $this->nullablePost('descripcion'),
            'cantidad' => (int) $this->request->getPost('cantidad'),
            'bodega' => (int) $this->request->getPost('bodega'),
            'anaquel' => (int) $this->request->getPost('anaquel'),
            'nivel' => (int) $this->request->getPost('nivel'),
            'tipo_control' => $this->request->getPost('tipo_control')
        ];
    }

    /**
     * obtiene un campo opcional nviado mediante POST,
     * elimina los espacios al inicio y al final del valor,
     * cuando el campo esta vacio devuelve NULL, evitando guardar cadenas vacias
     * dentro de la base de datos
     * 
     */
    private function nullablePost(string $field): ?string
    {
        $value = trim((string) $this->request->getPost($field));
        return $value === '' ? null : $value;
    }

    /**
     * define las reglas de validacion para los formularios de inventario
     * comprueba que los campos obligatorios esten presentes,
     * que los calores numericos sean validos y que el tipo de control corresponda  un elemento
     * retornable o consumible,
     * estas reglas se usan tanto para registras como para actualizar
     * 
     */
    private function rules(): array
    {
        return [
            'nombre' => 'required|max_length[150]',
            'cantidad' => 'required|integer|greater_than_equal_to[0]',
            'bodega' => 'required|integer|greater_than_equal_to[1]',
            'anaquel' => 'required|integer|greater_than_equal_to[1]',
            'nivel' => 'required|integer|greater_than_equal_to[1]',
            'tipo_control' => 'required|in_list[retornable,consumible]'
        ];
    }
    /**
     * guarda las fotografias enviadas para un elemento del inventario,
     * crea la carpeta de almacenamiento cuano todavia no existe, 
     * recorre los archivos recibidos y conserva unicamente imagenes validas con
     * extension JPG, JPGE, PNG o WEBP,
     * cada fotografia se guarda con un nombre aleatorio para evitar duplicados
     * y posteriormente se registra su relacion con el elemento corrspondiente en la base de datos,
     * los archivos invalidos, ya movidos o con una extension no permitida se omiten sin detener
     * el registro del elemento
     * 
     * 
     */
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

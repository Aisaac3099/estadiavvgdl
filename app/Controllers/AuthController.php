<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;

class AuthController extends Controller
{
    // Método para mostrar la vista del formulario de login
    public function showLogin()
    {
        return view('login/login');
    }

    // Método para procesar el formulario de login
    public function login()
    {
        $session = session();
        $model = new usuarioModel();

        //$email = $this->request->getPost('email');
        $login = $this->request->getPost('login'); //agregado
        $password = $this->request->getPost('password'); 
        // Busca el usuario por email
        //$user = $model->where('email', $email)->first(); 
        //busca por correo o por nombre de usuario
        $user = $model->where('email', $login) 
            ->orWhere('nombreUsuario', $login)
            ->first();

        if (!$user) {
            // Usuario no encontrado
            $session->setFlashdata('error', 'Usuario/Email o contraseña incorrectos.');
            return redirect()->back()->withInput();
        }
        
        
        // Verifica la contraseña
        if (!password_verify($password, $user['password'])) {
            // Contraseña incorrecta 
            $session->setFlashdata('error', 'Usuario/Email o contraseña incorrectos.');
            return redirect()->back()->withInput();
        }
        

        // Usuario autenticado, crea la sesión
        $sessionData = [
            'user_id'    => $user['id'], 
            'email' => $user['email'],
            'nombre' => $user['nombre'],
            'nombreUsuario' => $user['nombreUsuario'],
            'isLoggedIn' => true,

        ];
         
        $session->set($sessionData);
        
          


        // Redirige al usuario a la página de inicio
        return redirect()->to('/calendario');
    }

    public function logout()
    {
        $session = session();

        // Destruye la sesión actual
        $session->destroy();

        // Redirige al usuario a la página de login
        return redirect()->to('/login');
    }
}
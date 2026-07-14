<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
{
    $session = session();

    // Verifica si el usuario está logueado
    if ($session->get('isLoggedIn')) {
        // Redirige a la página principal después de login
        return redirect()->to('calendario/');
    } else {
        // Si no está logueado, muestra el login
        return view('login/login');
    }
}
}

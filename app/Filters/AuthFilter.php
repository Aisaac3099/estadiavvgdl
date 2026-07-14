<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    // Método que se ejecuta antes del controlador
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Si la sesión no tiene el valor 'isLoggedIn' o es falso, redirige al login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Por favor, inicia sesión para acceder.');
        }
    }

    // Método que se ejecuta después del controlador (no se necesita para este propósito)
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// Rutas de autenticación
$routes->get('login', 'AuthController::showLogin');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Todas las rutas protegidas por el filtro 'auth'
$routes->group('', ['filter' => 'auth'], function($routes){
    
    // Rutas para clientes
    $routes->get('clienteForm', 'ClienteController::showClienteForm');
    $routes->post('clienteForm', 'ClienteController::registerCliente');
    $routes->get('editCliente/(:num)', 'ClienteController::editCliente/$1');
    $routes->post('updateCliente/(:num)', 'ClienteController::updateCliente/$1');
    $routes->get('clientes', 'ClienteController::listarClientes');
    $routes->get('eliminarCliente/(:num)', 'ClienteController::eliminar/$1');

    // Rutas para servicios
    $routes->get('servicioForm', 'ServicioController::showServicioForm');
    $routes->post('servicioForm', 'ServicioController::registerServicio');
    $routes->get('editServicio/(:num)', 'ServicioController::editServicio/$1');
    $routes->post('updateServicio/(:num)', 'ServicioController::updateServicio/$1');
    $routes->get('servicios', 'ServicioController::listarServicios');
    $routes->get('eliminarServicio/(:num)', 'ServicioController::eliminar/$1');
    

    // Rutas para agendas
    $routes->get('agendaForm', 'AgendaController::showAgendaForm');
    $routes->post('agendaForm', 'AgendaController::createAgenda');
    $routes->get('editAgenda/(:num)', 'AgendaController::editAgenda/$1');
    $routes->post('updateAgenda/(:num)', 'AgendaController::updateAgenda/$1');
    $routes->get('agenda', 'AgendaController::listarAgenda');
    $routes->get('eliminarAgenda/(:num)', 'AgendaController::eliminar/$1');
    $routes->get('getServiciosPorCliente/(:num)', 'AgendaController::getServiciosPorCliente/$1'); 

    
    // Rutas del calendario
    $routes->get('calendario', 'AgendaController::showCalendar');
    $routes->get('eventos', 'AgendaController::getEventos');
    
    //Rutas para técnicos
    // Listar todos los técnicos
    $routes->get('/tecnicos', 'TecnicosController::listarTecnicos');
    // Mostrar formulario para agregar técnico
    $routes->get('/tecnicos/agregar', 'TecnicosController::showTecnicoForm');
    // Registrar nuevo técnico
    $routes->post('/tecnicos/agregar', 'TecnicosController::registerTecnico');
    // Mostrar formulario para editar técnico
    $routes->get('/tecnicos/editar/(:num)', 'TecnicosController::editTecnico/$1');
    // Actualizar técnico
    $routes->post('/tecnicos/editar/(:num)', 'TecnicosController::updateTecnico/$1');
    // Eliminar técnico
    $routes->get('/tecnicos/eliminar/(:num)', 'TecnicosController::eliminar/$1');

    // Rutas para usuarios
    // Listar usuarios activos
    $routes->get('/usuarios', 'UsuariosController::index');
    // Formulario de creación de usuario
    $routes->get('/usuarios/create', 'UsuariosController::create');
    // Guardar nuevo usuario
    $routes->post('/usuarios/store', 'UsuariosController::store');
    // Formulario de edición de usuario
    $routes->get('/usuarios/edit/(:num)', 'UsuariosController::edit/$1');
    // Actualizar usuario
    $routes->post('/usuarios/update/(:num)', 'UsuariosController::update/$1');
    // Desactivar usuario (soft delete)
    $routes->get('/usuarios/delete/(:num)', 'UsuariosController::delete/$1');

    //Ver servicios por cliente 25/09/2025
    $routes->get('/clientes/(:num)/servicios', 'ServiciosController::verServiciosCliente/$1');

    $routes->get('pruebaFCM', 'PruebaFCMController::index');
    $routes->get('testJSON', 'TestJSON::index');
    $routes->get('testFCMToken', 'TestFCMToken::index');
    $routes->get('diagnostico', 'PruebaFCMController::diagnosticarRuta');
    $routes->get('verificarPermisos', 'PruebaFCMController::verificarPermisos');
    $routes->get('testfcm', 'TestFCMController::index');
    $routes->post('fcm/registrar', 'FcmController::registrar');

    $routes->get('servicios/diagnosticoTokens', 'ServicioController::diagnosticoTokens');
    $routes->post('servicio/guardarToken', 'ServicioController::guardarToken');


    

    //NUEVA RUTA para agendar desde servicio
    $routes->get('agendaFormDesdeServicio/(:num)', 'AgendaController::agendaFormDesdeServicio/$1');
    
    $routes->post('createAgendaDesdeServicio', 'AgendaController::createAgendaDesdeServicio');
    $routes->post('createAgenda', 'AgendaController::createAgenda');

    //Rutas para los autos y sus servicios ** Isaac Gonzalez**
    $routes->get('servicios_autos', 'ServicioAutoController::index');
    $routes->post('autos/store', 'AutoController::store');
    $routes->post('servicios_autos/store', 'ServicioAutoController::store');
    $routes->get('autos','AutoController::index');
    
    
    //delete o dar de baja autos **Isaac Gonzalez**
    $routes->get('autos/baja/(:num)','AutoController::baja/$1');
    $routes->get('autos/reactivar/(:num)','AutoController::reactivar/$1');
    //rutas para auto **Isaac Gonzalez
    $routes->get('autos/edit/(:num)','AutoController::edit/$1');
    $routes->post('autos/update/(:num)','AutoController::update/$1');
    $routes->get('autos/eliminar-imagen/(:num)','AutoController::eliminarImagen/$1');
    $routes->get('autos/detalles/(:num)','AutoController::detalles/$1');
   

    //rutas servicios de auto **Isaac Gonzalez**
    $routes->get('servicios_autos/edit/(:num)','ServicioAutoController::edit/$1');
    $routes->post('servicios_autos/update/(:num)','ServicioAutoController::update/$1');
    $routes->get('servicios_autos/programar/(:num)', 'ServicioAutoController::programar/$1');
    $routes->get('servicios_autos/detalles/(:num)', 'ServicioAutoController::detalles/$1');
    $routes->get('servicios_autos/eliminar-evidencia/(:num)', 'ServicioAutoController::eliminarEvidencia/$1');


    // rutas notificaciones
    $routes->get('notificaciones/leer/(:num)', 'NotificacionController::leer/$1');
});
<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/sucursales', 'SucursalesController@index');
    $router->get('/sucursales/{id}', 'SucursalesController@show');

    //Usuarios
    $router->post('/usuarios', 'UsuariosController@store');
    $router->get('/usuarios', 'UsuariosController@index');
    $router->get('/usuarios/{id}', 'UsuariosController@show');
    $router->put('/usuarios/{id}', 'UsuariosController@update');
    $router->delete('/usuarios/{id}', 'UsuariosController@destroy');
    $router->post('/login', 'UsuariosController@login');

    //Productos
    $router->get('/productos', 'ProductosController@index');
    $router->post('/productos', 'ProductosController@store');
    $router->get('/productos/{nombre}', 'ProductosController@show');
    $router->put('/productos/{id}', 'ProductosController@update');
    $router->delete('/productos/{id}', 'ProductosController@destroy');

    //Formatos
    $router->get('/formatos', 'FormatosController@index');
    $router->post('/formatos', 'FormatosController@store');
    //$router->get('/formatos/{id}', 'FormatosController@show');
    $router->get('/formatos/{name}', 'FormatosController@getByName');

    //Proveedores
    $router->get('/proveedores', 'ProveedoresController@index');
    $router->post('/proveedores', 'ProveedoresController@store');
    $router->get('/proveedores/{nombre}', 'ProveedoresController@show');
    $router->put('/proveedores/{id}', 'ProveedoresController@update');
    $router->delete('/proveedores/{id}', 'ProveedoresController@destroy');

    //Ordenes de Compra
    $router->post('/ordenesCompra', 'OrdenComprasController@store');
    $router->get('/ordenesCompra', 'OrdenComprasController@listarOrdenes');
    $router->get('/ordenesCompra/{id}', 'OrdenComprasController@show');

    //Ordenes de Salida
    $router->post('/ordenesSalida', 'OrdenSalidasController@store');
    $router->get('/ordenesSalida', 'OrdenSalidasController@listarOrdenes');
    $router->get('/ordenesSalida/{id}', 'OrdenSalidasController@show');
});

$router->get('/test', function () {
    return (new \App\Models\PasswordManager())->hash('1234567890');
});

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
    $router->get('/login', 'UsuariosController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        //Sucursales
        $router->get('/sucursales/{id}', 'SucursalesController@show');
        //Productos
        $router->get('/productos', 'ProductosController@index');
        $router->get('/productos/{id}', 'ProductosController@show');
        //Formatos
        $router->get('/formatos', 'FormatosController@index');
        //$router->get('/formatos/{id}', 'FormatosController@show');
        $router->get('/formatos/{name}', 'FormatosController@getByName');
        //Proveedores
        $router->get('/proveedores', 'ProveedoresController@index');
        $router->get('/proveedores/{id}', 'ProveedoresController@show');
        //Stock
        $router->get('/stock', 'StockController@index');

        $router->group(['middleware' => 'admin'], function () use ($router) {
            //Sucursales
            $router->get('/sucursales', 'SucursalesController@index');
            //Usuarios
            $router->post('/usuarios', 'UsuariosController@store');
            $router->get('/usuarios', 'UsuariosController@index');
            $router->get('/usuarios/{id}', 'UsuariosController@show');
            $router->put('/usuarios/{id}', 'UsuariosController@update');
            $router->delete('/usuarios/{id}', 'UsuariosController@destroy');
            //Productos
            $router->post('/productos', 'ProductosController@store');
            $router->put('/productos/{id}', 'ProductosController@update');
            $router->delete('/productos/{id}', 'ProductosController@destroy');
            //Proveedores
            $router->post('/proveedores', 'ProveedoresController@store');
            $router->put('/proveedores/{id}', 'ProveedoresController@update');
            $router->delete('/proveedores/{id}', 'ProveedoresController@destroy');
            //Formatos
            $router->post('/formatos', 'FormatosController@store');
        });

        $router->group(['middleware' => 'jefe'], function () use ($router) {
            //Ordenes de Compra
            $router->post('/ordenesCompra', 'OrdenComprasController@store');
            $router->get('/ordenesCompra', 'OrdenComprasController@listarOrdenes');
            $router->get('/ordenesCompra/{id}', 'OrdenComprasController@show');
            $router->post('/ordenesCompra/guiaRemision', 'OrdenComprasController@registrarGuia');
        });

        $router->group(['middleware' => 'encargado'], function () use ($router) {
            //Ordenes de Salida
            $router->post('/ordenesSalida', 'OrdenSalidasController@store');
            $router->get('/ordenesSalida', 'OrdenSalidasController@listarOrdenes');
            $router->get('/ordenesSalida/{id}', 'OrdenSalidasController@show');
        });

        $router->get('/logout', 'UsuariosController@logout');
    });
});

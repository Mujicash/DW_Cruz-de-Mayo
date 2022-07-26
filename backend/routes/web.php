<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/sucursales', 'SucursalesController@index');
    $router->get('/sucursales/{id}', 'SucursalesController@show');
    $router->post('/usuarios', 'UsuariosController@store');
    $router->get('/usuarios', 'UsuariosController@index');
    $router->get('/usuarios/{id}', 'UsuariosController@show');
    $router->post('/login', 'UsuariosController@login');
});

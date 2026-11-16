<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router $router */

// Tanpa autentikasi: hanya membaca data
$router->get('customers',              'CustomerController@index');
$router->get('customers/{id:[0-9]+}',  'CustomerController@show');
$router->get('orders',                 'OrderController@index');
$router->get('orders/{id:[0-9]+}',     'OrderController@show');

// Wajib Basic Auth: INSERT, UPDATE, DELETE
$router->group(['middleware' => 'basicauth'], function () use ($router) {
    $router->post('customers',                'CustomerController@store');
    $router->put('customers/{id:[0-9]+}',     'CustomerController@update');
    $router->delete('customers/{id:[0-9]+}',  'CustomerController@destroy');

    $router->post('orders',                   'OrderController@store');
    $router->put('orders/{id:[0-9]+}',        'OrderController@update');
    $router->delete('orders/{id:[0-9]+}',     'OrderController@destroy');
});

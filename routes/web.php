<?php

use App\Http\Controllers\UserController;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api/v1'
], function ($router): void { // Add admin and user authenticated groups, isolate routes
    $router->post('/users', [
        'uses' => UserController::class . '@create',
    ]);
    $router->get('/users/{id}', [
        'uses' => UserController::class . '@get',
        'middleware' => 'auth'
    ]);
    $router->put('/users/{id}', [
        'uses' => UserController::class . '@update',
        'middleware' => 'auth'
    ]);
    $router->delete('/users/{id}', [
        'uses' => UserController::class . '@delete',
        'middleware' => 'auth'
    ]);
    $router->post('/users/api-key', [
        'uses' => UserController::class . '@createApiKey',
    ]);
});

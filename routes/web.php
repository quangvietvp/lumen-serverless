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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(
    ['prefix' => 'api'],
    function () use ($router) {
        $router->post('/register', "UserController@register");
        $router->post('/login', "UserController@login");
        $router->get('/logout', "UserController@logout");
        //$router->post('/dummy', "UserController@dummy");

        // For blog
        $router->get('blogs', ['uses' => 'BlogController@all']);

        $router->get('blogs/{id}', ['uses' => 'BlogController@detail']);

        $router->post('blogs', ['uses' => 'BlogController@create']);

        $router->delete('blogs/{id}', ['uses' => 'BlogController@delete']);

        $router->put('blogs/{id}', ['uses' => 'BlogController@update']);
    }
);
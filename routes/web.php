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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('register', ['uses' => 'UserController@register']);
        $router->post('sign-in', ['uses' => 'UserController@signIn']);
        $router->post('recover-password', ['uses' => 'UserController@recoverPassword']);
        $router->post('forget-password', ['uses' => 'UserController@forgetPassword']);
        $router->get('companies', ['middleware' => 'auth', 'uses' => 'UserController@showCompanies']);
        $router->post('companies', ['middleware' => 'auth', 'uses' => 'UserController@createCompany']);
    });
});

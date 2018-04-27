<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    // Default
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');

    // First version this API
    $app->get('/v1', function ($request, $handler) {
        return new JsonResponse(
            [
                'version' => '1.0.0',
                'description' => 'Access to all possible routes for regions, states and cities, with description of metadata in the response header.'
            ]
        );
    }, 'api.v1.get');
    
    // Routes for Regions
    $app->get('/v1/regions', App\Regions\GetAll::class, 'regions.all.get');
    $app->get('/v1/regions/{id_region}', App\Regions\Get::class, 'regions.get');
    $app->get('/v1/regions/{id_region}/states', App\States\GetAllByRegion::class, 'regions.states.all.get');
    $app->get('/v1/regions/{id_region}/states/{id_state}', App\States\GetOneByRegion::class, 'regions.states.get');
    $app->get('/v1/regions/{id_region}/states/{id_state}/cities', App\Cities\GetAllByRegionState::class, 'regions.states.cities.all.get');
    $app->get('/v1/regions/{id_region}/states/{id_state}/cities/{id_city}', App\Cities\GetOneByRegionState::class, 'regions.states.cities.get');

    // Routes for States
    $app->get('/v1/states', App\States\GetAll::class, 'states.all.get');
    $app->get('/v1/states/{id_state}', App\States\Get::class, 'states.get');
    $app->get('/v1/states/{id_state}/cities', App\Cities\GetAllByState::class, 'states.cities.all.get');
    $app->get('/v1/states/{id_state}/cities/{id_city}', App\Cities\GetOneByState::class, 'states.cities.get');

    // Routes for Cities
    $app->get('/v1/cities', App\Cities\GetAll::class, 'cities.all.get');
    $app->get('/v1/cities/{id_city}', App\Cities\Get::class, 'cities.get');
};

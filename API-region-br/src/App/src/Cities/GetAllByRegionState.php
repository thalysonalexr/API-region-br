<?php

declare(strict_types=1);

namespace App\Cities;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class GetAllByRegionState implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    private $tableGateway;
    private $adapter;

    public function __construct(TableGateway $tableGateway, Adapter $adapter = null)
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $adapter;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $idRegion = $request->getAttribute('id_region');
        $idState = $request->getAttribute('id_state');

        if (!filter_var($idRegion, FILTER_VALIDATE_INT) || !filter_var($idState, FILTER_VALIDATE_INT)) {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => 'Paramater "ID" is invalid',
                    'description' => 'Bad Request'
                ]
            );
        }

        try {
            $content = $this->adapter->query(
                'SELECT CITIES.id, CITIES.id_state, CITIES.name
                FROM REGIONS, STATES, CITIES
                WHERE REGIONS.id = STATES.id_region
                AND STATES.id = CITIES.id_state
                AND REGIONS.id = ?
                AND STATES.id = ?',
                [$idRegion, $idState]
            )->toArray();
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'code' => 500,
                    'message' => 'Error processing request',
                    'description' => 'Server Error'
                ]
            );
        }

        if (count($content) === 0) {
            return new JsonResponse(
                [
                    'code' => 404,
                    'message' => 'This state was not found',
                    'description' => 'Not Found'
                ],
            404);
        }
        return new JsonResponse($content, 200);
    }
}

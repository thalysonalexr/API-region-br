<?php

declare(strict_types=1);

namespace App\Cities;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetAllByRegion implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    private $tableGateway;
    private $adapter;

    public function __construct($tableGateway, $adapter)
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $adapter;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $idRegion = $request->getAttribute('id_region');

        if ( ! filter_var($idRegion, FILTER_VALIDATE_INT)) {
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
                'SELECT * FROM CITIES
                WHERE CITIES.id_state IN
                (
                    SELECT STATES.id as id_state FROM STATES
                    WHERE STATES.id_region = ?
                )',
                [$idRegion]
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

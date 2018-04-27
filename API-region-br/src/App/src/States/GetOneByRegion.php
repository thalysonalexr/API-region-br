<?php

declare(strict_types=1);

namespace App\States;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetOneByRegion implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    private $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
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
            $content = $this->tableGateway->select(['id' => $idState, 'id_region' => $idRegion])->toArray();
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

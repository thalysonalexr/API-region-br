<?php

declare(strict_types=1);

namespace App\Cities;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetAll implements MiddlewareInterface
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
        $content = $this->tableGateway->select()->toArray();
        return new JsonResponse($content);
    }
}

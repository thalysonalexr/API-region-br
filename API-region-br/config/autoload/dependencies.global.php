<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,

            // Regions
            App\Regions\Get::class => App\Regions\Factory::class,
            App\Regions\GetAll::class => App\Regions\Factory::class,

            // States
            App\States\Get::class => App\States\Factory::class,
            App\States\GetAll::class => App\States\Factory::class,
            App\States\GetAllByRegion::class => App\States\Factory::class,
            App\States\GetOneByRegion::class => App\States\Factory::class,

            // Cities
            App\Cities\Get::class => App\Cities\Factory::class,
            App\Cities\GetAll::class => App\Cities\Factory::class,
            App\Cities\GetAllByRegionState::class => App\Cities\Factory::class,
            App\Cities\GetOneByRegionState::class => App\Cities\Factory::class,
            App\Cities\GetAllByState::class => App\Cities\Factory::class,
            App\Cities\GetOneByState::class => App\Cities\Factory::class,
        ],
    ],
];

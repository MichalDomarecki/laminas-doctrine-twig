<?php

declare(strict_types=1);

namespace Application;

use Application\View\Twig\PhpMissingFunctionsExtension;
use Doctrine\Persistence\ObjectManager;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use ZendTwig\Service\TwigExtensionFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            //twig
            PhpMissingFunctionsExtension::class => TwigExtensionFactory::class,
        ],
        'aliases' => [
            ObjectManager::class => 'doctrine.entitymanager.orm_default',
        ],
    ],
    'zend_twig' => [
        'extensions' => [
            PhpMissingFunctionsExtension::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.twig',
//            'application/index/index' => __DIR__ . '/../view/application/index/index.twig',
            'error/404'               => __DIR__ . '/../view/error/404.twig',
            'error/index'             => __DIR__ . '/../view/error/500.twig',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];

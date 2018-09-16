<?php
/**
 * Created by PhpStorm.
 * User: nightracer
 * Date: 16.09.2018
 * Time: 14:24
 */

namespace Main;

use Main\Controller\ControllerFactory;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\MainController::class => ControllerFactory::class
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'main' => __DIR__ . '/../view'
        ]
    ],
    'router' => [
        'routes' => [
            'main' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/main[/:action[/:id]]',
                    'constrains' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller' => Controller\MainController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ],
            ],

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Main\Entity' => 'entity',
                ],
            ],
        ],
    ],
];
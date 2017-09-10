<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

return [

    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'types' => [
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                ]
            ],
        ],
        'driver' => [
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                ]
            ]
        ]
    ],

    Module::class => [
        'listeners' => [
        ],
    ]
];

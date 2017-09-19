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
                    // Some Custom Types
                ],
                'datetime_functions' => [
                    'date' => ORM\Query\AST\Functions\DateFunction::class,
                    'day' => ORM\Query\AST\Functions\DayFunction::class,
                    'year' => ORM\Query\AST\Functions\YearFunction::class,
                ],
                'string_functions' => [
                    'concat_ws' => ORM\Query\AST\Functions\ConcatWSFunction::class,
                    'month' => ORM\Query\AST\Functions\MonthFunction::class,
                ],
                'numeric_functions' => [
                    'geo' => ORM\Query\AST\Functions\GeoFunction::class
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                    'host' => 'localhost',
                    'user' => '',
                    'password' => '',
                    'dbname' => '',
                    'charset' => 'utf8',
                    'driverOptions' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
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

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
                    DBAL\Types\ArrayResolverType::NAME =>
                        DBAL\Types\ArrayResolverType::class
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
                    'charset' => 'utf8',
                    'driverOptions' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ]
            ],
        ],
    ],

    Module::class => [
        'listeners' => [
        ],
    ]
];

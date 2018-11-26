<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'types' => [
                    DBAL\Types\ArrayResolverType::NAME =>
                        DBAL\Types\ArrayResolverType::class,
                    DBAL\Types\PublishingStateType::NAME =>
                        DBAL\Types\PublishingStateType::class
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
                'driverClass' => Driver::class,
                'params' => [
                    'host' => 'localhost',
                    'user' => null,
                    'password' => null,
                    'dbname' => null,
                    'charset' => 'utf8',
                    'driverOptions' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ]
            ],
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\Element\PublishingState::class =>
                InvokableFactory::class,
        ]
    ]
];

<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Doctrine;

use Ramsey\Uuid\Doctrine\UuidType;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ConfigProvider
 * @package MSBios\Doctrine
 */
class ConfigProvider extends \MSBios\ConfigProvider
{
    /**
     * @inheritdoc
     *
     * @return array
     */
    public function __invoke(): array
    {
        return ArrayUtils::merge(parent::__invoke(), [
            'doctrine' => $this->getDoctrineConfig(),
            'form_elements' => $this->getFormElementsConfig(),
        ]);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                ListenerAggregate::class =>
                    InvokableFactory::class
            ]
        ];
    }

    /**
     * @return array
     */
    public function getDoctrineConfig(): array
    {
        return [
            'configuration' => [
                'orm_default' => [
                    'types' => [
                        DBAL\Types\ArrayResolverType::NAME =>
                            DBAL\Types\ArrayResolverType::class,
                        DBAL\Types\PublishingStateType::NAME =>
                            DBAL\Types\PublishingStateType::class,
                        DBAL\Types\GenderType::NAME =>
                            DBAL\Types\GenderType::class,
                        UuidType::NAME => UuidType::class
                    ],
                    'datetime_functions' => [
                        'date' =>
                            ORM\Query\AST\Functions\DateFunction::class,
                        'day' =>
                            ORM\Query\AST\Functions\DayFunction::class,
                        'year' =>
                            ORM\Query\AST\Functions\YearFunction::class,
                    ],
                    'string_functions' => [
                        'concat_ws' =>
                            ORM\Query\AST\Functions\ConcatWSFunction::class,
                        'month' =>
                            ORM\Query\AST\Functions\MonthFunction::class,
                    ],
                    'numeric_functions' => [
                        'geo' =>
                            ORM\Query\AST\Functions\GeoFunction::class
                    ],
                ],
            ],
            'connection' => [
                'orm_default' => [
                    'driverClass' => null, // \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                    'params' => [
                        'host' => 'localhost',
                        'user' => null,
                        'password' => null,
                        'dbname' => null,
                        'charset' => 'utf8',
                        'driverOptions' => [
                            // \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                        ]
                    ]
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFormElementsConfig(): array
    {
        return [
            'factories' => [
                Form\Element\Gender::class =>
                    InvokableFactory::class,
                Form\Element\PublishingState::class =>
                    InvokableFactory::class,
            ]
        ];
    }
}
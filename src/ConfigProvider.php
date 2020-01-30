<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Doctrine;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Stdlib\ArrayUtils;
use Oro\ORM\Query\AST\Functions\Cast;
use Oro\ORM\Query\AST\Functions\DateTime\ConvertTz;
use Oro\ORM\Query\AST\Functions\Numeric\Pow;
use Oro\ORM\Query\AST\Functions\Numeric\Round;
use Oro\ORM\Query\AST\Functions\Numeric\Sign;
use Oro\ORM\Query\AST\Functions\Numeric\TimestampDiff;
use Oro\ORM\Query\AST\Functions\SimpleFunction;
use Oro\ORM\Query\AST\Functions\String\ConcatWs;
use Oro\ORM\Query\AST\Functions\String\DateFormat;
use Oro\ORM\Query\AST\Functions\String\GroupConcat;
use Oro\ORM\Query\AST\Functions\String\Replace;
use Ramsey\Uuid\Doctrine\UuidType;

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
                            SimpleFunction::class,
                        'time' =>
                            SimpleFunction::class,
                        'timestamp' =>
                            SimpleFunction::class,
                        'convert_tz' =>
                            ConvertTz::class,
                    ],
                    'string_functions' => [
                        'md5' =>
                            SimpleFunction::class,
                        'group_concat' =>
                            GroupConcat::class,
                        'cast' =>
                            Cast::class,
                        'concat_ws' =>
                            ConcatWs::class,
                        'replace' =>
                            Replace::class,
                        'date_format' =>
                            DateFormat::class
                    ],
                    'numeric_functions' => [
                        'geo' =>
                            ORM\Query\AST\Functions\GeoFunction::class,
                        'timestampdiff' =>
                            TimestampDiff::class,
                        'dayofyear' =>
                            SimpleFunction::class,
                        'dayofmonth' =>
                            SimpleFunction::class,
                        'dayofweek' =>
                            SimpleFunction::class,
                        'week' =>
                            SimpleFunction::class,
                        'day' =>
                            SimpleFunction::class,
                        'hour' =>
                            SimpleFunction::class,
                        'minute' =>
                            SimpleFunction::class,
                        'month' =>
                            SimpleFunction::class,
                        'quarter' =>
                            SimpleFunction::class,
                        'second' =>
                            SimpleFunction::class,
                        'year' =>
                            SimpleFunction::class,
                        'sign' =>
                            Sign::class,
                        'pow' =>
                            Pow::class,
                        'round' =>
                            Round::class,
                        'ceil' =>
                            SimpleFunction::class,
                    ],
                ],
            ],
            'connection' => [
                'orm_default' => [
                    // 'driverClass' => null, // \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                    // 'params' => [
                    //     'host' => 'localhost',
                    //     'user' => null,
                    //     'password' => null,
                    //     'dbname' => null,
                    //     'charset' => 'utf8',
                    //     'driverOptions' => [
                    //         // \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    //     ]
                    // ]
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
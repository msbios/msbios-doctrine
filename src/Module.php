<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

/**
 * Class Module
 * @package MSBios\Doctrine
 */
class Module extends \MSBios\Module
{
    /** @const VERSION */
    const VERSION = '1.0.22';

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @inheritdoc
     *
     * @return array|mixed|\Traversable
     */
    public function getConfig()
    {
        /** @var ConfigProvider $provider */
        $provider = new ConfigProvider;
        return [
            'service_manager' => $provider->getDependencyConfig(),
            'doctrine' => $provider->getDoctrineConfig(),
            'form_elements' => $provider->getFormElementsConfig(),
            'listeners' => [
                ListenerAggregate::class =>
                    ListenerAggregate::class
            ]
        ];
    }

    ///**
    // * @inheritdoc
    // *
    // * @param EventInterface $e
    // * @throws \Doctrine\DBAL\DBALException
    // */
    //public function onBootstrap(EventInterface $e)
    //{
    //    /** @var MySqlPlatform $platform */
    //    $platform = $e
    //        ->getTarget()
    //        ->getServiceManager()
    //        ->get(EntityManager::class)
    //        ->getConnection()
    //        ->getDatabasePlatform();
    //    $platform->registerDoctrineTypeMapping('enum', 'string');
    //}
}

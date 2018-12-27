<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;

/**
 * Class Module
 * @package MSBios\Doctrine
 */
class Module extends \MSBios\Module implements BootstrapListenerInterface
{
    /** @const VERSION */
    const VERSION = '1.0.14';

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
     * @param EventInterface $e
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MySqlPlatform $platform */
        $platform = $e
            ->getTarget()
            ->getServiceManager()
            ->get(EntityManager::class)
            ->getConnection()
            ->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
    }
}

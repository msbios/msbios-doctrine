<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use MSBios\ModuleInterface;
use Zend\EventManager\EventInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\Mvc\ApplicationInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package MSBios\Doctrine
 */
class Module implements ModuleInterface
{
    /** @const VERSION */
    const VERSION = '1.0.10';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var ApplicationInterface $target */
        $target = $e->getTarget();

        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $target->getServiceManager();

        /** @var MySqlPlatform $platform */
        $platform = $serviceManager
            ->get(EntityManager::class)
            ->getConnection()
            ->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }
}

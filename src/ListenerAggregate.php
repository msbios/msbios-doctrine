<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\Mvc\MvcEvent;

/**
 * Class ListenerAggregate
 * @package MSBios\Doctrine
 */
class ListenerAggregate extends AbstractListenerAggregate implements BootstrapListenerInterface
{
    /**
     * @inheritdoc
     *
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap'], $priority);
    }

    /**
     * @inheritDoc
     *
     * @param EventInterface $e
     * @throws DBALException
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
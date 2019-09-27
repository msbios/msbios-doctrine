<?php
/**
 * Created by PhpStorm.
 * User: judzhin
 * Date: 9/27/19
 * Time: 9:12 PM
 */

namespace MSBios\Doctrine;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class ListenerAggregate
 * @package MSBios\Doctrine
 */
class ListenerAggregate extends AbstractListenerAggregate
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
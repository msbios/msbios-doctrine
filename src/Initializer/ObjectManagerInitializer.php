<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\Initializer;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class ObjectManagerInitializer
 * @package MSBios\Doctrine\Initializer
 */
class ObjectManagerInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     * @param object $instance
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof ObjectManagerAwareInterface) {
            $instance->setObjectManager(
                $container->get(EntityManager::class)
            );
        }
    }

    /**
     * @param $an_array
     * @return ObjectManagerInitializer
     */
    public static function __set_state($an_array)
    {
        return new self();
    }
}

<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Initializer\InitializerInterface;

/**
 * Class ObjectManagerInitializer
 * @package MSBios\Doctrine
 */
class ObjectManagerInitializer implements InitializerInterface
{
    /**
     * @inheritdoc
     *
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
     * @inheritdoc
     *
     * @param $an_array
     * @return ObjectManagerInitializer
     */
    public static function __set_state($an_array)
    {
        return new self();
    }
}

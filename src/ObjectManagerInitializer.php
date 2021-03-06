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
     * @inheritDoc
     *
     * @param ContainerInterface $container
     * @param object $instance
     */
    public function __invoke(ContainerInterface $container, $instance): void
    {
        if ($instance instanceof ObjectManagerAwareInterface) {
            $instance->setObjectManager(
                $container->get(EntityManager::class)
            );
        }
    }

    /**
     * @inheritDoc
     *
     * @param $an_array
     * @return static
     */
    public static function __set_state($an_array): self
    {
        return new self();
    }
}

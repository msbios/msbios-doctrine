<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entity
 *
 * @package MSBios\Doctrine
 * @ORM\MappedSuperclass
 */
abstract class Entity implements EntityInterface, IdentifierableAwareInterface
{
    use IdentifierAwareTrait;
}

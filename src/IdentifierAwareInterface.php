<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine;

/**
 * Interface IdentifierAwareInterface
 * @package MSBios\Doctrine
 */
interface IdentifierAwareInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): IdentifierAwareInterface;
}
